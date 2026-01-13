<?php
/**
 * Speculative Loading Debug and Local Disable.
 *
 * Adds a debug console log and disables speculative loading on the current page
 * when enabled via theme settings.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Inject debug JS and suppress speculation rules if debugging is enabled.
 */
function maybe_log_speculative_debug() {
	if (
		! get_option( 'enable_speculative_logging', false ) ||
		is_user_logged_in()
	) {
		return;
	}

	add_action(
		'wp_head',
		function () {
			?>
		<script>
			if (document.prerendering) {
				console.log("Page is prerendering");
			} else if (performance.getEntriesByType("navigation")[0]?.activationStart > 0) {
				console.log("Page has already prerendered");
			} else {
				console.log("This page load was not using prerendering");
			}
		</script>
			<?php
		},
		100
	);

	add_filter( 'wp_speculation_rules_configuration', '__return_empty_array' );
}
add_action( 'wp', __NAMESPACE__ . '\\maybe_log_speculative_debug' );
