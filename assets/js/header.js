// Add the .js-enabled class to the <body> when JavaScript is detected
document.addEventListener('DOMContentLoaded', () => {
	document.body.classList.add('js-enabled');
});

// Cache the header element to avoid re-querying the DOM on each scroll
const powderHeader = document.querySelector(".site-header");

// Initialize the last scroll top position
let powderLastScrollTop = 0;

// Function to handle the scroll event
function handleScroll() {
	if (powderHeader) { // Only proceed if the header exists
		const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

		// Compare the new scroll position with the last scroll position
		if (scrollTop > powderLastScrollTop) {
			// If scrolling down, hide the header
			powderHeader.style.top = "-100%";
		} else {
			// If scrolling up, show the header
			powderHeader.style.top = "0";
		}

		// Update the last scroll position
		powderLastScrollTop = scrollTop;
	}
}

// Throttle the scroll event to avoid performance issues
let isThrottled = false;
const powderThrottleDuration = 100; // milliseconds

window.addEventListener("scroll", () => {
	if (!isThrottled) {
		handleScroll();
		isThrottled = true;

		// Set a timeout to un-throttle after a certain duration
		setTimeout(() => {
			isThrottled = false;
		}, powderThrottleDuration);
	}
});