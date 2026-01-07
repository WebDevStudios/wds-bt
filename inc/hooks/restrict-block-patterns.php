<?php
/**
 * Restrict block patterns to theme-specific patterns only.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Restrict patterns from the Dotcom library by overriding the patterns source site.
 *
 * @return string The string to disable the Dotcom patterns source.
 */
function restrict_block_editor_patterns() {
	return 'disable-dotcom-patterns-source';
}
add_filter( 'a8c_override_patterns_source_site', __NAMESPACE__ . '\restrict_block_editor_patterns' );
add_filter( 'should_load_remote_block_patterns', '__return_false' );
