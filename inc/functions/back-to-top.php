<?php
/**
 * Adds a "Back to Top" button with smooth scroll functionality.
 *
 * The button is dynamically added to the footer and is shown when the user
 * scrolls down the page. Includes vanilla JavaScript for smooth scrolling and
 * visibility toggling, and basic CSS styling for positioning and appearance.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Outputs the "Back to Top" button HTML, JavaScript, and CSS.
 *
 * The button is hidden by default and is shown when the user scrolls down
 * 300px from the top of the page. The button, when clicked, scrolls the page
 * smoothly back to the top.
 *
 * @return void
 */
function add_back_to_top_button() {
	?>
	<!-- Back to Top Button -->
	<a href="#top" class="back-to-top" aria-label="Back to Top">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
			<path d="M12 2l-7 7h5v9h4v-9h5l-7-7z"/>
		</svg>
	</a>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const backToTopButton = document.querySelector('.back-to-top');

			window.addEventListener('scroll', function() {
				if (window.scrollY > 300) { // Show the button after scrolling 300px.
					backToTopButton.classList.add('show');
				} else {
					backToTopButton.classList.remove('show');
				}
			});

			// Smooth scrolling when clicking the button.
			backToTopButton.addEventListener('click', function(e) {
				e.preventDefault();
				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			});
		});
	</script>
	<style>
		.back-to-top {
			background: var(--wp--preset--color--primary-500);
			border-radius: 5px;
			bottom: 1.25rem;
			color: var(--wp--preset--color--white);
			display: none; /* Initially hidden */
			padding: 0.625rem;
			position: fixed;
			right: 1.25rem;
			text-decoration: none;
			z-index: 1000; /* Ensure it's above other elements */
		}

		.back-to-top:hover {
			background: var(--wp--preset--color--primary-700);
		}

		.back-to-top.show {
			align-items: center;
			display: flex;
			justify-content: center;
		}
	</style>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\add_back_to_top_button' );
