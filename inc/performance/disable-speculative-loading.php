<?php
/**
 * Disable Speculative Loading for WDS-BT Theme.
 *
 * Applies a global toggle to disable speculative loading site-wide.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Disables speculative loading for the entire site if the global setting is enabled.
 *
 * @param bool $enabled Whether speculative loading is enabled.
 * @return bool
 */
function maybe_disable_speculative_loading( $enabled ) {
	if ( get_option( 'disable_speculative_loading_all', false ) ) {
		return false;
	}
	return $enabled;
}
add_filter( 'wp_speculative_loading_enabled', __NAMESPACE__ . '\maybe_disable_speculative_loading' );

/**
 * Admin notice to show that speculative loading is globally disabled.
 */
function show_global_disable_notice() {
	if ( get_option( 'disable_speculative_loading_all', false ) ) {
		echo '<div class="notice notice-warning is-dismissible"><p>';
		echo esc_html__( 'Speculative Loading is currently disabled globally.', 'wdsbt' );
		echo '</p></div>';
	}
}
add_action( 'admin_notices', __NAMESPACE__ . '\show_global_disable_notice' );
