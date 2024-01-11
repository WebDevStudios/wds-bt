<?php
/**
 * Functions for the Powder WordPress theme.
 *
 * @package Powder
 * @author  Brian Gardner
 * @license GNU General Public License v2 or later
 * @link    https://briangardner.com/
 */

if ( ! function_exists( 'powder_setup' ) ) {

	/**
	 * Initialize theme defaults and registers support for WordPress features.
	 */
	function powder_setup() {

		// Enqueue editor style sheet.
		add_editor_style( get_template_directory_uri() . '/style.css' );

		// Disable core block inline styles.
		add_filter( 'should_load_separate_core_block_assets', '__return_false' );

		// Remove core block patterns support.
		remove_theme_support( 'core-block-patterns' );

	}
}
add_action( 'after_setup_theme', 'powder_setup' );

/**
 * Enqueue theme style sheet.
 */
function powder_enqueue_style_sheet() {

	wp_enqueue_style( 'powder', get_template_directory_uri() . '/style.css', array(), wp_get_theme( 'powder' )->get( 'Version' ) );

}
add_action( 'wp_enqueue_scripts', 'powder_enqueue_style_sheet' );

/**
 * Enqueue theme header javascript.
 */
function powder_enqueue_header_javascript() {

	wp_enqueue_script( 'header', get_template_directory_uri() . '/assets/js/header.js', array('jquery'), '1.0', true );

}
add_action( 'wp_enqueue_scripts', 'powder_enqueue_header_javascript' );

/**
 * Register block styles.
 */
function powder_register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'minimal' => __( 'Minimal', 'powder' ),
			'text'    => __( 'Text Only', 'powder' )
		),
		'core/columns' => array(
			'column-reverse' => __( 'Reverse', 'powder' ),
		),
		'core/cover' => array(
			'gradient' => __( 'Gradient', 'powder' )
		),
		'core/group' => array(
			'shadow-light' => __( 'Shadow (Light)', 'powder' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'powder' ),
		),
		'core/image' => array(
			'shadow-light' => __( 'Shadow (Light)', 'powder' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'powder' ),
		),
		'core/list' => array(
			'no-style' => __( 'No Style', 'powder' ),
		),
		'core/social-links' => array(
			'outline' => __( 'Outline', 'powder' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}

}
add_action( 'init', 'powder_register_block_styles' );

/**
 * Register block pattern categories.
 */
function powder_register_pattern_categories() {

	register_block_pattern_category(
		'content',
		array(
			'label'       => __( 'Content', 'powder' ),
			'description' => __( 'A collection of content patterns designed for Powder.', 'powder' ),
		)
	);
	register_block_pattern_category(
		'hero',
		array(
			'label'       => __( 'Hero', 'powder' ),
			'description' => __( 'A collection of hero patterns designed for Powder.', 'powder' ),
		)
	);
	register_block_pattern_category(
		'page',
		array(
			'label'       => __( 'Pages', 'powder' ),
			'description' => __( 'A collection of page patterns designed for Powder.', 'powder' ),
		)
	);
	register_block_pattern_category(
		'template',
		array(
			'label'       => __( 'Templates', 'powder' ),
			'description' => __( 'A collection of template patterns designed for Powder.', 'powder' ),
		)
	);

}
add_action( 'init', 'powder_register_pattern_categories' );

/**
 * Register block variations.
 */
function powder_enqueue_block_variations() {
	wp_enqueue_script(
		'powder-enqueue-block-variations',
		get_template_directory_uri() . '/assets/js/variations.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-element', 'wp-primitives' ),
		wp_get_theme()->get( 'Version' ),
		false
	);
}
add_action( 'enqueue_block_editor_assets', 'powder_enqueue_block_variations' );

/**
 * Enqueue custom block stylesheets
 *
 * @since Powder
 * @return void
 */
function powder_block_stylesheets() {
	/**
	 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
	 * for a specific block. These stylesheets will only be loaded when the block is rendered
	 * (both in the editor and on the front end), improving performance
	 * and reducing the amount of data requested by visitors.
	 *
	 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
	 */

	// Enqueue styles from the core block folder.
	foreach ( glob( get_parent_theme_file_path( 'assets/css/blocks/core/*.css' ) ) as $stylesheet ) {
		$block_name = basename( $stylesheet, '.css' );
		$handle     = 'powder-' . $block_name . '-style';

		wp_enqueue_block_style(
			'core/' . $block_name,
			array(
				'handle' => $handle,
				'src'    => get_parent_theme_file_uri( 'assets/css/blocks/core/' . $block_name . '.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => $stylesheet,
			)
		);
	}

	// Enqueue styles from the custom block folder.
	foreach ( glob( get_parent_theme_file_path( 'assets/css/blocks/custom/*.css' ) ) as $custom_stylesheet ) {
		$custom_block_name = basename( $custom_stylesheet, '.css' );
		$custom_handle     = 'powder-custom-' . $custom_block_name . '-style';

		wp_enqueue_block_style(
			'powder/' . $custom_block_name,
			array(
				'handle' => $custom_handle,
				'src'    => get_parent_theme_file_uri( 'assets/css/blocks/custom/' . $custom_block_name . '.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => $custom_stylesheet,
			)
		);
	}
}
add_action( 'init', 'powder_block_stylesheets' );
