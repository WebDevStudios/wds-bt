<?php
/**
 * Enqueue custom block styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueue custom block stylesheets
 *
 * @return void
 */
function enqueue_block_stylesheet() {
	/**
	 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
	 * for a specific block. These stylesheets will only be loaded when the block is rendered
	 * (both in the editor and on the front end), improving performance
	 * and reducing the amount of data requested by visitors.
	 *
	 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
	 */

	// Enqueue styles from the core block folder.
	foreach ( glob( get_parent_theme_file_path( '/build/css/blocks/*.css' ) ) as $stylesheet ) {
		$block_name = basename( $stylesheet, '.css' );
		$handle     = 'wdsbt-' . $block_name . '-style';

		wp_enqueue_block_style(
			'core/' . $block_name,
			array(
				'handle' => $handle,
				'src'    => get_parent_theme_file_uri( '/build/css/blocks/' . $block_name . '.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => $stylesheet,
			)
		);
	}
}

add_filter( 'init', __NAMESPACE__ . '\enqueue_block_stylesheet', 10, 1 );

/**
 * Forces separate loading of all block stylesheets.
 *
 * This ensures that block styles don't conflict with other styles
 * on the page.
 *
 * https://developer.wordpress.org/reference/hooks/should_load_separate_core_block_assets/
 *
 * @return bool Always returns true.
 */
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

/**
 * Enqueues block-specific stylesheets for relevant blocks.
 *
 * This function searches for stylesheets in the `assets/css` folder
 * that follow the naming convention `block-namespace--block-name.css`.
 * Matching stylesheets are then enqueued for their corresponding blocks.
 *
 * @return void
 */
function enqueue_block_styles() {

	$theme_version = wp_get_theme()->get( 'Version' );

	$block_styles = get_block_stylesheets();

	foreach ( $block_styles as $block_name => $stylesheet ) {
		$args = array(
			'handle' => $block_name,
			'path'   => $stylesheet['path'],
			'src'    => $stylesheet['assets'],
			'ver'    => $theme_version . '.' . filemtime( $stylesheet['path'] ),
		);
		wp_enqueue_block_style( $block_name, $args );
	}
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\enqueue_block_styles' );

/**
 * Retrieves information about block stylesheets.
 *
 * @return array An array containing information about block stylesheets.
 */
function get_block_stylesheets() {
	$css_dir             = get_stylesheet_directory() . '/assets/css';
	$exclude_stylesheets = array( 'style.css', 'style-rtl.css' );

	$css_files = array_diff(
		glob( "$css_dir/*.css" ),
		$exclude_stylesheets
	);

	return array_map(
		function ( $css_file ) use ( $css_dir ) {
			$pattern = '/(.+)--(.+)\.css/i';
			preg_match( $pattern, basename( $css_file ), $matches );

			$block_name = $matches[1] . '/' . $matches[2];

			return [
				'path' => $css_file,
				'src'  => str_replace( $css_dir, get_stylesheet_directory_uri() . '/assets/css', $css_file ),
			];
		},
		$css_files
	);
}

/**
 * Registers all block folders found in the `blocks` directory.
 *
 * @return void
 */
function register_blocks() {
	$block_folders = glob( get_stylesheet_directory() . '/blocks/*', GLOB_ONLYDIR );
	foreach ( $block_folders as $block_folder ) {
		register_block_type( $block_folder );
	}
}

add_action( 'init', __NAMESPACE__ . '\register_blocks' );

/**
 * Enqueue assets for specific blocks when requested.
 *
 * @param string|array $blocks The block name(s) to enqueue assets for, e.g. 'core/group'. Accepts a single block name or an array of block names.
 *
 * @return void
 */
function enqueue_block_assets( $blocks ) {
	$block_registry = \WP_Block_Type_Registry::get_instance();

	if ( ! is_array( $blocks ) ) {
		$blocks = array( $blocks );
	}

	foreach ( $blocks as $block ) {
		$block_type = $block_registry->get_registered( $block );

		if ( ! $block_type ) {
			wp_debug_log( "Block name '$block' not found for enqueueing assets." );
			continue;
		}

		foreach ( $block_type->style_handles as $style_handle ) {
			wp_enqueue_style( $style_handle );
		}

		foreach ( $block_type->script_handles as $script_handle ) {
			wp_enqueue_script( $script_handle );
		}
	}
}
