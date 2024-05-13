<?php
/**
 * Functions to disable core Gutenberg blocks.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Prevents editors from adding unregistered core blocks to content or pages.
 *
 * @return void
 */
function remove_core_blocks_gutenberg_frontend() {
	wp_enqueue_script(
		'unregister_core_blocks',
		get_bloginfo( 'template_directory' ) . '/assets/js/global/unregister-core-embed.js',
		array( 'wp-blocks', 'wp-dom-ready' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\remove_core_blocks_gutenberg_frontend' );
