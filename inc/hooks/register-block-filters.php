<?php
/**
 * Functions to enqueue scripts in the block editor and frontend.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueues the filters.js script in the block editor.
 *
 * @return void
 */
function enqueue_editor_filters_script() {
	wp_enqueue_script(
		'editor_filters_script',
		get_template_directory_uri() . '/build/js/filters.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_editor_filters_script' );

/**
 * Enqueues the filters.js script on the frontend.
 *
 * @return void
 */
function enqueue_frontend_filters_script() {
	wp_enqueue_script(
		'frontend_filters_script',
		get_template_directory_uri() . '/build/js/filters.js',
		array( 'wp-dom-ready' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_frontend_filters_script' );
