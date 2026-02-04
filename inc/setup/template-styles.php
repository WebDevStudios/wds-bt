<?php
/**
 * Enqueue template-specific styles.
 *
 * Loads only the CSS for the current template to reduce unused CSS and improve
 * Lighthouse performance. Template styles are built to build/css/templates/.
 * Any .css file in that directory is discovered automatically. Reserved names
 * (404, search, archive) map to WordPress conditionals. If the key is a
 * registered post type (e.g. portfolio.css for post type "portfolio"), the
 * stylesheet loads on single and archive for that CPT. All other keys map to
 * page templates by slug (e.g. block-showcase.css for template page-block-showcase).
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
	$templates_dir = get_template_directory() . '/build/css/templates';
	$css_files     = glob( $templates_dir . '/*.css' );

	if ( ! $css_files ) {
		return;
	}

	$template_file = null;
	$handle_suffix = null;

	foreach ( $css_files as $stylesheet_path ) {
		$key = basename( $stylesheet_path, '.css' );

		// Skip RTL variants (e.g. 404-rtl.css); we only need the main file.
		if ( str_contains( $key, '-rtl' ) ) {
			continue;
		}

		$matches = false;

		if ( '404' === $key && is_404() ) {
			$matches = true;
		} elseif ( 'search' === $key && is_search() ) {
			$matches = true;
		} elseif ( 'archive' === $key && is_archive() ) {
			$matches = true;
		} elseif ( post_type_exists( $key ) && ( is_singular( $key ) || is_post_type_archive( $key ) ) ) {
			$matches = true;
		} elseif ( is_singular( 'page' ) ) {
			$page_template = get_page_template_slug( get_queried_object_id() );
			$matches       = $page_template && (
				$page_template === $key
				|| $page_template === $key . '.html'
				|| str_contains( $page_template, $key )
			);
			if ( ! $matches ) {
				$matches = is_page_template( $key . '.html' ) || is_page_template( 'templates/' . $key . '.html' );
			}
		}

		if ( $matches ) {
			$template_file = basename( $stylesheet_path );
			$handle_suffix = $key;
			break;
		}
	}

	if ( ! $template_file || ! $handle_suffix ) {
		return;
	}

	$path = $templates_dir . '/' . $template_file;
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
