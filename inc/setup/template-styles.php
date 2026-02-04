<?php
/**
 * Enqueue template-specific styles.
 *
 * Loads only the CSS for the current template to reduce unused CSS and improve
 * Lighthouse performance. Template styles are built to build/css/templates/.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueue template-specific stylesheet when the current template matches.
 *
 * @return void
 */
function enqueue_template_styles() {
	$template_file = null;
	$handle_suffix = null;

	if ( is_404() ) {
		$template_file = '404.css';
		$handle_suffix = '404';
	} elseif ( is_search() ) {
		$template_file = 'search.css';
		$handle_suffix = 'search';
	} elseif ( is_archive() ) {
		$template_file = 'archive.css';
		$handle_suffix = 'archive';
	} elseif ( is_page_template( 'page-block-showcase.html' ) ) {
		$template_file = 'block-showcase.css';
		$handle_suffix = 'block-showcase';
	}

	if ( ! $template_file || ! $handle_suffix ) {
		return;
	}

	$path = get_template_directory() . '/build/css/templates/' . $template_file;
	$uri  = get_template_directory_uri() . '/build/css/templates/' . $template_file;

	if ( ! is_readable( $path ) ) {
		return;
	}

	$handle = 'wdsbt-template-' . $handle_suffix;
	$ver    = (string) filemtime( $path );

	wp_enqueue_style(
		$handle,
		$uri,
		array( 'wdsbt-styles' ),
		$ver
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_template_styles', 15 );
