<?php
/**
 * This file adds functions to the Powder WordPress theme.
 *
 * @package Powder
 * @author  Brian Gardner
 * @license GNU General Public License v2 or later
 * @link    https://briangardner.com/
 */

if ( ! function_exists( 'powder_setup' ) ) {

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook.
	 *
	 * @since 0.5.0
	 */
	function powder_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'powder', get_template_directory() . '/languages' );

		// Enqueue editor styles.
		add_editor_style( get_template_directory_uri() . '/style.css' );

		// Disable loading core block inline styles.
		add_filter( 'should_load_separate_core_block_assets', '__return_false' );

		// Remove core block patterns.
		remove_theme_support( 'core-block-patterns' );

	}
}
add_action( 'after_setup_theme', 'powder_setup' );

/**
 * Enqueue theme style sheet.
 *
 * @since 0.5.0
 */
function powder_enqueue_style_sheet() {

	wp_enqueue_style(
		'powder',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'powder' )->get( 'Version' )
	);

}
add_action( 'wp_enqueue_scripts', 'powder_enqueue_style_sheet' );

/**
 * Register block styles.
 *
 * @since 0.5.0
 */
function powder_register_block_styles() {

	$block_styles = array(
		'core/columns' => array(
			'column-reverse' => __( 'Reverse', 'powder' ),
		),
		'core/group' => array(
			'shadow-light' => __( 'Shadow', 'powder' ),
			'shadow-solid' => __( 'Solid', 'powder' ),
		),
		'core/image' => array(
			'shadow-light' => __( 'Shadow', 'powder' ),
			'shadow-solid' => __( 'Solid', 'powder' ),
		),
		'core/list' => array(
			'no-style' => __( 'No Style', 'powder' ),
		),
		'core/navigation-link' => array(
			'outline' => __( 'Outline', 'powder' ),
		),
		'core/quote' => array(
			'shadow-light' => __( 'Shadow', 'powder' ),
			'shadow-solid' => __( 'Solid', 'powder' ),
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
 *
 * @since 0.9.3
 */
function powder_register_block_pattern_categories() {

	register_block_pattern_category(
		'content',
		array(
			'label'       => __( 'Content', 'powder' ),
			'description' => __( 'A variety of content sections created with blocks.', 'powder' ),
		)
	);
	register_block_pattern_category(
		'page',
		array(
			'label'       => __( 'Page', 'powder' ),
			'description' => __( 'A variety of page designs created with a combination of blocks.', 'powder' ),
		)
	);
	register_block_pattern_category(
		'social-media',
		array(
			'label'       => __( 'Social Media', 'powder' ),
			'description' => __( 'A variety of designs to showcase social media followers and links.', 'powder' ),
		)
	);

}

add_action( 'init', 'powder_register_block_pattern_categories' );