(function() {
  const scriptUrl = new URL(document.currentScript.src);
  const apiUrl = `${scriptUrl.origin}/api/send`;
  const websiteId = document.currentScript.getAttribute("website");
  const countedEventAttribute = "data-counted-event";
  const debounceDelay = 500;
  const throttleLimit = 1e3;
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
      eventData
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
  function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
      clearTimeout(timeoutId);
      timeoutId = setTimeout(() => func.apply(this, args), delay);
    };
  }
  function throttle(func, limit) {
    let inThrottle = false;
    return function(...args) {
      if (!inThrottle) {
        func.apply(this, args);
        inThrottle = true;
        setTimeout(() => inThrottle = false, limit);
      }
    };
  }
  function handleTrackingClick(event) {
    const target = event.target;
    const linkElement = target.tagName === "A" ? target : target.closest("a");
    const eventData = (linkElement == null ? void 0 : linkElement.getAttribute(countedEventAttribute)) || target.getAttribute(countedEventAttribute);
    let openInNewTab = false;
    if (eventData) {
      if (linkElement) {
        openInNewTab = linkElement.target === "_blank" || event.ctrlKey || event.shiftKey || event.metaKey || event.button && event.button === 1;
        event.preventDefault();
      }
      const debouncedEventHandler = debounce(() => {
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
            if (openInNewTab) {
              window.open(linkElement.href, "_blank");
            }
          }
        });
      }, debounceDelay);
      debouncedEventHandler();
    }
  }
  function observeUrlChange() {
    let oldHref = document.location.href;
    const body = document.querySelector("body");
    const observer = new MutationObserver((mutations) => {
      mutations.forEach(() => {
        if (oldHref !== document.location.href) {
          oldHref = document.location.href;
          setTimeout(() => {
            track();
          }, throttleLimit);
        }
      });
    });
    observer.observe(body, { childList: true, subtree: true });
  }
  function setupEventListeners() {
    window.addEventListener("DOMContentLoaded", () => {
      observeUrlChange();
      track();
    });
    window.addEventListener("popstate", () => track());
    window.addEventListener("click", throttle(handleTrackingClick, throttleLimit));
    window.addEventListener("visibilitychange", () => track("activity"));
  }
  setupEventListeners();
})();
