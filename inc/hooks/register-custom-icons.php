<?php
/**
 * Register custom icons.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers custom icons if the 'icon-block' plugin is active.
 */
function register_custom_icons() {
	if ( is_plugin_active( 'icon-block/icon-block.php' ) ) {
		wp_enqueue_script(
			'register-custom-icons',
			get_parent_theme_file_uri( '/assets/js/global/custom-icons.js' ),
			array( 'wp-i18n', 'wp-hooks', 'wp-dom' ),
			wp_get_theme()->get( 'Version' ),
			true
		);

		wp_enqueue_style( 'wp-edit-blocks' );
	}
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\register_custom_icons' );
