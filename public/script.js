(function() {
  const SCRIPT_URL = new URL(document.currentScript.src);
  const API_ENDPOINT = `${SCRIPT_URL.origin}/api/send`;
  const WEBSITE_ID = document.currentScript.getAttribute("website");
  const COUNTED_EVENT_ATTRIBUTE = "data-counted-event";
  async function sendStatistics(data) {
    try {
      const response = await fetch(API_ENDPOINT, {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
      });
      if (response.ok) {
        const { token } = await response.json();
        sessionStorage.setItem("stat-token", token);
      } else {
        console.error("Failed to send statistics:", response.status);
        throw new Error("Failed to send statistics");
      }
    } catch (error) {
      console.error("Error sending statistics:", error);
      throw error;
    }
  }
  function track(type = "view", eventData = null) {
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
      eventData
    };
    const token = sessionStorage.getItem("stat-token");
    if (token) {
      data.token = token;
    }
    console.log(data);
    try {
      sendStatistics(data);
    } catch (error) {
      console.error("Error sending statistics:", error);
      throw error;
    }
  }
  function debounce(func, delay) {
    let timeoutId;
    return function() {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(func, delay);
    };
  }
  function throttle(func, limit) {
    let inThrottle;
    return function() {
      if (!inThrottle) {
        func();
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }
  function trackClicks(event) {
    const target = event.target;
    const linkElement = target.tagName === "A" ? target : target.closest("a");
    const eventData = (linkElement == null ? void 0 : linkElement.getAttribute(COUNTED_EVENT_ATTRIBUTE)) || target.getAttribute(COUNTED_EVENT_ATTRIBUTE);
    let isBlank = false;
    if (eventData) {
      if (linkElement) {
        isBlank = linkElement.target === "_blank" || event.ctrlKey || event.shiftKey || event.metaKey || event.button && event.button === 1;
        event.preventDefault();
      }
      const eventHandler = debounce(() => {
        new Promise((resolve, reject) => {
          try {
            track("event", eventData);
            resolve();
          } catch (error) {
            reject(error);
          }
        }).catch((error) => {
          console.error("Error sending statistics:", error);
        }).finally(() => {
          if (linkElement) {
            if (isBlank) {
              window.open(linkElement.href, "_blank");
            }
          }
        });
      }, 500);
      eventHandler();
    }
  }
  const observeUrlChange = () => {
    let oldHref = document.location.href;
    const body = document.querySelector("body");
    const observer = new MutationObserver((mutations) => {
      mutations.forEach(() => {
        if (oldHref !== document.location.href) {
          oldHref = document.location.href;
          track();
        }
      });
    });
    observer.observe(body, { childList: true, subtree: true });
  };
  function setupEventListeners() {
    window.addEventListener("DOMContentLoaded", () => {
      observeUrlChange();
      track();
    });
    window.addEventListener("popstate", () => track());
    window.addEventListener("click", (e) => {
      throttle(trackClicks(e), 1e3);
    });
    window.addEventListener("visibilitychange", () => track("activity"));
  }
  setupEventListeners();
})();
