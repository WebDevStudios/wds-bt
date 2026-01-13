<?php
/**
 * Register custom block styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Register block variations.
 */
function register_block_variations() {
	wp_enqueue_script(
		'wdsbt-enqueue-block-variations',
		get_template_directory_uri() . '/build/js/variations.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-element', 'wp-primitives' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\register_block_variations' );
