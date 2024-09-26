<?php
/**
 * Enqueue scripts and styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueue scripts and styles.
 *
 * @author WebDevStudios
 */
function scripts() {
	$asset_file_path = get_template_directory() . '/build/js/index.asset.php';
	$asset_version   = wp_get_theme()->get( 'Version' );

	if ( is_readable( $asset_file_path ) ) {
		$asset_file = include $asset_file_path;
	} else {
		$asset_file = array(
			'version'      => '0.1.0',
			'dependencies' => array( 'wp-polyfill' ),
		);
	}

	// Register styles & scripts.
	wp_enqueue_style( 'freeman-styles', get_stylesheet_directory_uri() . '/build/css/style.css', array(), $asset_version );
	wp_enqueue_script( 'freeman-scripts', get_stylesheet_directory_uri() . '/build/js/index.js', $asset_file['dependencies'], $asset_version, true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );
