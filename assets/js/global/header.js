document.addEventListener('DOMContentLoaded', function () {
	document.body.classList.add('js-enabled');
});

const siteHeader = document.querySelector('.site-header');
let lastScrollTop = 0;
let isThrottled = false;
const throttleDuration = 100; // milliseconds

function handleScroll() {
	if (siteHeader) {
		const scrollTop =
			window.pageYOffset || document.documentElement.scrollTop;

		if (scrollTop > lastScrollTop) {
			siteHeader.style.top = '-100%';
		} else {
			siteHeader.style.top = '0';
		}

		lastScrollTop = scrollTop;
	}
}

function throttleScroll() {
	if (!isThrottled) {
		handleScroll();
		isThrottled = true;

		setTimeout(function () {
			isThrottled = false;
		}, throttleDuration);
	}
}

window.addEventListener('scroll', throttleScroll);
