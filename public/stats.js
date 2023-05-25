// Replace `API_ENDPOINT` with the actual API endpoint URL
const API_ENDPOINT = 'http://statify.test/api/send';

// Function to send statistics to the API endpoint
function sendStatistics(data) {
  fetch(API_ENDPOINT, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(data),
  })
    .then(response => {
      if (response.ok) {
        console.log('Statistics sent successfully!');
      } else {
        console.error('Failed to send statistics:', response.status);
      }
    })
    .catch(error => {
      console.error('Error sending statistics:', error);
    });
}

function getWebsiteIdFromScript() {
  const currentScript = document.currentScript;
  if (currentScript && currentScript.hasAttribute('data-website-id')) {
    return currentScript.getAttribute('data-website-id');
  }
  return null;
}

// Function to track a page view
function trackPageView() {

  const websiteId = getWebsiteIdFromScript();
  if (!websiteId) {
    console.error('Missing data-website-id attribute!');
    return;
  }

  const data = {
    type: 'visit',
    timestamp: new Date().toISOString(),
    url: window.location.href,
    website_id: websiteId,
    // Add any other relevant statistics you want to send
    // For example, user agent, referrer, etc.
  };

  sendStatistics(data);
}

// Call the trackPageView function to send statistics when the page loads
trackPageView();
