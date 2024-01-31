<?php
/**
 * Registers custom block pattern categories for the WDS BT theme.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers custom block pattern categories for the WDS BT theme.
 */
function register_custom_block_pattern_categories() {

	register_block_pattern_category(
		'content',
		array(
			'label'       => __( 'Content', 'wdsbt' ),
			'description' => __( 'A collection of content patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'hero',
		array(
			'label'       => __( 'Hero', 'wdsbt' ),
			'description' => __( 'A collection of hero patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'page',
		array(
			'label'       => __( 'Pages', 'wdsbt' ),
			'description' => __( 'A collection of page patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'template',
		array(
			'label'       => __( 'Templates', 'wdsbt' ),
			'description' => __( 'A collection of template patterns designed for WDS BT.', 'wdsbt' ),
		)
	);

	// Remove default patterns.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern_categories', 9 );
