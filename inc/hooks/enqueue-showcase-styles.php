<?php
/**
 * Enqueue block showcase styles when templates or shortcodes are used.
 *
 * Block-theme template slugs may be unreliable on the frontend; templates also
 * load CSS via enqueue_template_styles(). This complements that for shortcodes
 * on ordinary pages.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueue showcase stylesheets after template-specific enqueue runs.
 *
 * @return void
 */
function enqueue_showcase_styles_when_needed() {
	if ( ! is_singular( 'page' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof \WP_Post ) {
		return;
	}

	$page_template      = get_page_template_slug( $post->ID );
	$template_is_string = is_string( $page_template );

	$uses_block_tpl = false;
	if ( $template_is_string && '' !== $page_template ) {
		if ( false !== strpos( $page_template, 'page-block-showcase' )
			|| false !== strpos( $page_template, 'block-showcase' ) ) {
			$uses_block_tpl = true;
		}
	}

	$content = $post->post_content;
	if ( ! is_string( $content ) ) {
		$content = '';
	}

	$needs_block_styles = $uses_block_tpl || has_shortcode( $content, 'wdsbt_block_showcase' );

	if ( ! $needs_block_styles ) {
		return;
	}

	wdsbt_enqueue_single_showcase_style(
		true,
		'block-showcase',
		'wdsbt-showcase-block',
		array( 'wdsbt-styles' ),
		showcase_template_stylesheet_already_enqueued( 'block-showcase' )
	);
}

/**
 * True when enqueue_template_styles() already enqueued this templates/*.css file.
 *
 * @param string $slug Template CSS basename (e.g. block-showcase).
 * @return bool
 */
function showcase_template_stylesheet_already_enqueued( $slug ) {
	$slug   = basename( $slug, '.css' );
	$handle = 'wdsbt-template-' . $slug;

	return wp_style_is( $handle, 'enqueued' );
}

/**
 * Enqueue one showcase CSS bundle if missing.
 *
 * @param bool     $want            Whether this bundle is wanted.
 * @param string   $basename        CSS basename without extension.
 * @param string   $handle          Stylesheet handle.
 * @param string[] $deps            Dependencies.
 * @param bool     $skip_via_template Already loaded by template styles.
 * @return void
 */
function wdsbt_enqueue_single_showcase_style( $want, $basename, $handle, $deps, $skip_via_template ) {
	if ( ! $want || $skip_via_template ) {
		return;
	}

	if ( wp_style_is( $handle, 'enqueued' ) ) {
		return;
	}

	$path = get_template_directory() . '/build/css/templates/' . $basename . '.css';
	$uri  = get_template_directory_uri() . '/build/css/templates/' . $basename . '.css';

	if ( ! is_readable( $path ) ) {
		return;
	}

	wp_enqueue_style(
		$handle,
		$uri,
		$deps,
		(string) filemtime( $path )
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_showcase_styles_when_needed', 17 );
