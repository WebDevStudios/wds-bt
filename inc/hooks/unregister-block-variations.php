<?php
/**
 * Unregister custom block styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Unregister block variations.
 */
function unregister_block_variations() {

	wp_enqueue_script(
		'unregistered-blocks-list',
		get_template_directory_uri() . '/assets/js/unregistered-blocks-list.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		wp_get_theme()->get( 'Version' ),
		false
	);
}

add_filter( 'enqueue_block_editor_assets', __NAMESPACE__ . '\unregister_block_variations', 10, 1 );
