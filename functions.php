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

		// Load editor styles.
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

}
add_action( 'init', 'powder_register_pattern_categories' );
