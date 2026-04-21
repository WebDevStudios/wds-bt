<?php
/**
 * Register Custom Icons
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/** Editor assets when icon-block is active. */
function register_custom_icons() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	if ( ! is_plugin_active( 'icon-block/icon-block.php' ) ) {
		return;
	}

	wp_enqueue_script(
		'register-custom-icons',
		get_parent_theme_file_uri( '/assets/js/global/custom-icons.js' ),
		array( 'wp-i18n', 'wp-hooks', 'wp-dom' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_enqueue_style( 'wp-edit-blocks' );
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\register_custom_icons' );
