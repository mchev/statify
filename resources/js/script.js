(function () {
  "use strict";

  // Define constants
  const scriptUrl = new URL(document.currentScript.src);
  const apiUrl = `${scriptUrl.origin}/api/send`;
  const websiteId = document.currentScript.getAttribute("website");
  const countedEventAttribute = "data-counted-event";
  const debounceDelay = 500; // Adjust the delay time as needed
  const throttleLimit = 1000;

  /**
   * Send statistics to the API
   * @param {Object} data - The data to be sent
   */
  function sendStatistics(data) {
    if (navigator.sendBeacon) {
      navigator.sendBeacon(apiUrl, JSON.stringify(data));
    } else {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", apiUrl, false);
      xhr.setRequestHeader("Content-Type", "text/plain;charset=UTF-8");
      xhr.send(data);
    }
  }

  /**
   * Track a page view or event
   * @param {string} type - The type of tracking (view or event)
   * @param {string|null} eventData - The event data to be tracked
   */
  function track(type = "view", eventData = null) {
    if (!websiteId) {
      console.error("Missing website attribute!");
      return;
    }

    const trackingData = {
      type,
      url: window.location.href,
      referrer: document.referrer,
      title: document.title,
      screen: `${window.screen.width}x${window.screen.height}`,
      language: window.navigator.language,
      website: websiteId,
      eventData,
    };

    const token = sessionStorage.getItem("stat-token");
    if (token) {
      trackingData.token = token;
    }

    console.log(trackingData);

    try {
      sendStatistics(trackingData);
    } catch (error) {
      console.error("Error sending statistics:", error);
      throw error;
    }
  }

  /**
   * Debounces a function
   * @param {Function} func - The function to be debounced
   * @param {number} delay - The delay in milliseconds
   * @returns {Function} - The debounced function
   */
  function debounce(func, delay) {
    let timeoutId;
    return function (...args) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
  }

  /**
   * Throttles a function
   * @param {Function} func - The function to be throttled
   * @param {number} limit - The limit in milliseconds
   * @returns {Function} - The throttled function
   */
  function throttle(func, limit) {
    let inThrottle = false;
    return function (...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => (inThrottle = false), limit);
      }
    };
  }

  /**
   * Tracks clicks and sends statistics
   * @param {Event} event - The click event
   */
  function handleTrackingClick(event) {
    const target = event.target;
    const linkElement = target.tagName === "A" ? target : target.closest("a");
    const eventData =
      linkElement?.getAttribute(countedEventAttribute) ||
      target.getAttribute(countedEventAttribute);
    let openInNewTab = false;

    if (eventData) {
      if (linkElement) {
        openInNewTab =
          linkElement.target === "_blank" ||
          event.ctrlKey ||
          event.shiftKey ||
          event.metaKey ||
          (event.button && event.button === 1);
        event.preventDefault();
      }

      // Debounced/Throttled event handler
      const debouncedEventHandler = debounce(() => {
        new Promise((resolve, reject) => {
          try {
            track("event", eventData);
            resolve();
          } catch (error) {
            reject(error);
          }
        })
          .catch((error) => {
            console.error("Error sending statistics:", error);
          })
          .finally(() => {
            if (linkElement) {
              if (openInNewTab) {
                window.open(linkElement.href, "_blank");
              } else {
                // window.location.href = linkElement.href;
              }
            }
          });
      }, debounceDelay);

      debouncedEventHandler();
    }
  }

  /**
   * Observes URL changes and tracks them
   */
  function observeUrlChange() {
    let oldHref = document.location.href;
    const body = document.querySelector("body");
    const observer = new MutationObserver((mutations) => {
      mutations.forEach(() => {
        if (oldHref !== document.location.href) {
          oldHref = document.location.href;
          setTimeout(() => {
            track(); // Update the title property with a delay
          }, throttleLimit); // Adjust the delay time as needed
        }
      });
    });
    observer.observe(body, { childList: true, subtree: true });
  }

  /**
   * Sets up event listeners
   */
  function setupEventListeners() {
    window.addEventListener("DOMContentLoaded", () => {
      observeUrlChange();
      track();
    });
    window.addEventListener("popstate", () => track());
    window.addEventListener("click", throttle(handleTrackingClick, throttleLimit));
    window.addEventListener("visibilitychange", () => track("activity"));
  }

  // Initialize the event listeners
  setupEventListeners(window);
})(window);