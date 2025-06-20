<?php
/**
 * Speculative Loading Conditional Exclusions for WDS-BT Theme.
 *
 * Excludes sensitive/dynamic pages from being prerendered or prefetched
 * if the conditional toggle is enabled and global disable is not active.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Conditionally exclude sensitive paths from speculative loading.
 *
 * @param array $paths Array of paths to exclude from prerendering.
 * @return array
 */
function exclude_sensitive_pages( array $paths ): array {
	if (
		get_option( 'disable_speculative_loading', true ) &&
		! get_option( 'disable_speculative_loading_all', false )
	) {
		$paths = array_merge(
			$paths,
			array(
				'/cart/',
				'/checkout/',
				'/my-account/',
				'/account/',
				'/wp-login.php',
			)
		);
	}
	return $paths;
}
add_filter( 'wp_speculation_rules_href_exclude_paths', __NAMESPACE__ . '\exclude_sensitive_pages' );
