<?php
/**
 * Dynamically register block pattern categories based on subfolders in /patterns.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers dynamic block pattern categories.
 *
 * @return void
 */
function register_dynamic_block_pattern_categories() {
	$patterns_dir = get_template_directory() . '/patterns/';
	if ( ! is_dir( $patterns_dir ) ) {
		return;
	}

	// Get all subfolders in /patterns.
	$subfolders = glob( $patterns_dir . '*', GLOB_ONLYDIR );

	foreach ( $subfolders as $folder_path ) {
		$slug = basename( $folder_path );
		// Skip hidden folders or folders starting with a dot.
		if ( strpos( $slug, '.' ) === 0 ) {
			continue;
		}
		// Register the category using the folder name as the slug and a capitalized label.
		register_block_pattern_category(
			$slug,
			[ 'label' => ucwords( str_replace( [ '-', '_' ], ' ', $slug ) ) ]
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\register_dynamic_block_pattern_categories' );
