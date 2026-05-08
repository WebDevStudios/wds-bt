<?php
/**
 * Accordion behaviour and hash navigation for the block showcase.
 *
 * Mirrors talogy footer script: opens the correct accordion panel for URL fragments
 * and keeps toggles usable without the bundled accordion script.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Print showcase UI helpers when shortcodes rendered blocks on this page load.
 *
 * @return void
 */
function showcase_ui_footer_scripts() {
	if ( empty( $GLOBALS['wdsbt_block_showcase_rendered'] ) ) {
		return;
	}
	?>
<script>
(function() {
	'use strict';
	function revealHashTarget(showcase) {
		var hash = window.location.hash;
		if (!hash || hash.length < 2) return;
		var id;
		try {
			id = decodeURIComponent(hash.slice(1));
		} catch (e) {
			return;
		}
		if (!id) return;
		var el = document.getElementById(id);
		if (!el || !showcase.contains(el)) return;
		var item = el.closest('.wp-block-accordion-item');
		var panel = item ? item.querySelector('.wp-block-accordion-panel') : null;
		var toggle = item ? item.querySelector('.wp-block-accordion-heading__toggle') : null;
		if (panel && toggle) {
			toggle.setAttribute('aria-expanded', 'true');
			panel.setAttribute('aria-hidden', 'false');
			panel.style.display = 'block';
			item.classList.add('is-open');
		}
		requestAnimationFrame(function() {
			el.scrollIntoView({ behavior: 'smooth', block: 'start' });
		});
	}
	function init() {
		var showcase = document.querySelector('.wdsbt-block-showcase');
		if (!showcase) return;
		revealHashTarget(showcase);
		window.addEventListener('hashchange', function() {
			revealHashTarget(showcase);
		});
		showcase.addEventListener('click', function(e) {
			var toggle = e.target.closest('.wp-block-accordion-heading__toggle');
			if (!toggle || !showcase.contains(toggle)) return;
			var item = toggle.closest('.wp-block-accordion-item');
			if (!item) return;
			var panel = item.querySelector('.wp-block-accordion-panel');
			if (!panel) return;
			e.preventDefault();
			e.stopPropagation();
			var isExpanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
			panel.setAttribute('aria-hidden', isExpanded ? 'true' : 'false');
			panel.style.display = isExpanded ? 'none' : 'block';
			if (isExpanded) {
				item.classList.remove('is-open');
			} else {
				item.classList.add('is-open');
			}
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
</script>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\showcase_ui_footer_scripts', 20 );
