'use strict';

// Define constants
const scriptURL = new URL(document.currentScript.src);
const API_ENDPOINT = `${scriptURL.origin}/api/send`;
const WEBSITE_ID = document.currentScript.getAttribute("website");


// Override pushState to dispatch location change event
const oldPushState = history.pushState;
history.pushState = function pushState() {
  const ret = oldPushState.apply(this, arguments);
  window.dispatchEvent(new Event("locationchange"));
  return ret;
};

// Send statistics to the API
async function sendStatistics(data) {
  try {
    const response = await fetch(API_ENDPOINT, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });

    if (response.ok) {
      console.log("Statistics sent successfully!");
      const { token } = await response.json();
      sessionStorage.setItem("stat-token", token);
    } else {
      console.error("Failed to send statistics:", response.status);
    }
  } catch (error) {
    console.error("Error sending statistics:", error);
  }
}

// Track a page view
function trackPageView(type = "view") {
  if (!WEBSITE_ID) {
    console.error("Missing website attribute!");
    return;
  }

  const data = {
    type,
    url: window.location.href,
    referrer: document.referrer,
    title: document.title,
    screen: `${window.screen.width}x${window.screen.height}`,
    language: window.navigator.language,
    website: WEBSITE_ID,
  };

  const token = sessionStorage.getItem("stat-token");
  if (token) {
    data.token = token;
  }

  console.log(data);

  sendStatistics(data);
}

// Track clicks on links
function trackClicks(event) {
  if (event.target.tagName.toLowerCase() === "a") {
    console.log("Link clicked");
  }
}

// Track activity on visibility change
function trackVisibilityChange() {
  trackPageView("activity");
}

// Event listeners
function setupEventListeners() {
  window.addEventListener("DOMContentLoaded", () => trackPageView());
  window.addEventListener("popstate", () => trackPageView());
  window.addEventListener("locationchange", () => trackPageView());
  window.addEventListener("click", trackClicks);
  window.addEventListener("visibilitychange", trackVisibilityChange);
}

// Initialize the event listeners
setupEventListeners();