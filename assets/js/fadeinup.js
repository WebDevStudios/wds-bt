// Get references to DOM elements with .is-style-fadeinup class
const fadeinElements = document.querySelectorAll('.is-style-fadeinup');

// Function to handle element visibility in the viewport
const handleFadeInOnScroll = () => {
    fadeinElements.forEach(el => {
        if (!el.classList.contains('in-view')) {
            const rect = el.getBoundingClientRect();
            if (rect.top <= window.innerHeight && rect.bottom >= 0) {
                el.classList.add('in-view'); // Trigger the animation when element is in view
            }
        }
    });
};

// Throttle function to limit the rate at which a function can fire
const throttle = (func, delay) => {
    let lastCall = 0;
    return (...args) => {
        const now = Date.now();
        if (now - lastCall >= delay) {
            lastCall = now;
            func(...args);
        }
    };
};

// Throttle the scroll handler to improve performance
const throttledHandleFadeInOnScroll = throttle(handleFadeInOnScroll, 100);

// Add optimized scroll listener for element visibility handling
window.addEventListener('scroll', throttledHandleFadeInOnScroll);

// Perform initial checks when the DOM content is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    handleFadeInOnScroll(); // Call handleFadeInOnScroll() directly for the initial check
});
