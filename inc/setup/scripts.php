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

	wp_enqueue_style( 'wdsbt-styles', get_stylesheet_directory_uri() . '/build/css/style.css', array(), $asset_version );
	wp_enqueue_script( 'wdsbt-scripts', get_stylesheet_directory_uri() . '/build/js/index.js', $asset_file['dependencies'], $asset_version, true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );

/**
 * Add defer attribute to theme scripts.
 *
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag.
 */
function defer_theme_scripts( $tag, $handle ) {
	if ( 'wdsbt-scripts' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\defer_theme_scripts', 10, 2 );
