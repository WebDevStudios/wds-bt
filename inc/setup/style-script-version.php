<?php
/**
 * Version Control for scripts and styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Append file modification timestamp to CSS/JS assets for cache busting.
 *
 * @param string $src The original source URL of the enqueued asset.
 * @return string The modified source URL with version parameter.
 *
 * @author WebDevStudios
 */
function style_script_version( $src ) {

	$style_file = get_stylesheet_directory() . '/style.css';

	if ( file_exists( $style_file ) ) {
		$version = filemtime( $style_file );

		if ( strpos( $src, 'ver=' ) !== false ) {
			$src = add_query_arg( 'ver', $version, $src );
		}
	}

	return esc_url( $src );
}

/**
 * Hook the custom versioning function to CSS and JS asset loading.
 */
function css_js_versioning() {
	add_filter( 'style_loader_src', __NAMESPACE__ . '\style_script_version', 9999 );

	add_filter( 'script_loader_src', __NAMESPACE__ . '\style_script_version', 9999 );
}

add_action( 'init', __NAMESPACE__ . '\css_js_versioning' );
