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
		get_template_directory_uri() . '/assets/js/variations/index.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-element', 'wp-primitives' ),
		wp_get_theme()->get( 'Version' ),
		false
	);
}

add_filter( 'enqueue_block_editor_assets', __NAMESPACE__ . '\register_block_variations', 10, 1 );
