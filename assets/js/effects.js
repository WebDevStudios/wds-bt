let lastScrollTop = 0;

window.addEventListener("scroll", function() {
    const header = document.querySelector(".site-header");
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop) {
        // If scrolling down, hide the header.
        header.style.top = "-100%";
    } else {
        // If scrolling up, show the header.
        header.style.top = "0";
    }

    lastScrollTop = scrollTop;
});