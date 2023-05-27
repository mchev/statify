// Define constants
const API_ENDPOINT = `${document.currentScript.src.split("/").slice(0, -1).join("/")}/api/send`;
const WEBSITE_ID = document.currentScript.getAttribute("website");

// Record start time
const startTime = performance.now();

// Override pushState to dispatch location change event
const oldPushState = history.pushState;
history.pushState = function pushState() {
  const ret = oldPushState.apply(this, arguments);
  window.dispatchEvent(new Event('locationchange'));
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
      console.log(await response.json());
    } else {
      console.error("Failed to send statistics:", response.status);
    }
  } catch (error) {
    console.error("Error sending statistics:", error);
  }
}

// Function to track a page view
function trackPageView() {
  if (!WEBSITE_ID) {
    console.error("Missing website attribute!");
    return;
  }

  const data = {
    type: "view",
    url: window.location.href,
    title: document.title,
    screen: `${window.screen.width}x${window.screen.height}`,
    language: window.navigator.language,
    history: window.history,
    website: WEBSITE_ID,
    load_time: performance.now() - startTime,
  };

  console.log(data);

  sendStatistics(data);
}

// Function to track clicks on links
function trackClicks(event) {
  if (event.target.tagName.toLowerCase() === "a") {
    console.log('Link clicked');
  }
}

// Event listeners
window.addEventListener("DOMContentLoaded", trackPageView);
window.addEventListener("popstate", trackPageView);
window.addEventListener("locationchange", trackPageView);
window.addEventListener("click", trackClicks);