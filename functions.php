<?php
/**
 * Functions for the WDS BT WordPress theme.
 *
 * @package wdsbt
 * @author  WebDevStudios
 * @license GNU General Public License v3
 * @link    https://webdevstudios.com/
 */

if ( ! function_exists( 'wdsbt_setup' ) ) {

	/**
	 * Initialize theme defaults and register support for WordPress features.
	 */
	function wdsbt_setup() {

		// Enqueue editor style sheet.
		add_editor_style( get_template_directory_uri() . '/style.css' );

		// Disable core block inline styles.
		add_filter( 'should_load_separate_core_block_assets', '__return_false' );

		// Remove core block patterns support.
		remove_theme_support( 'core-block-patterns' );

	}
}
add_action( 'after_setup_theme', 'wdsbt_setup' );

/**
 * Enqueue theme style sheet.
 */
function wdsbt_enqueue_style_sheet() {

	wp_enqueue_style( 'wdsbt', get_template_directory_uri() . '/style.css', array(), wp_get_theme( 'wdsbt' )->get( 'Version' ) );

}
add_action( 'wp_enqueue_scripts', 'wdsbt_enqueue_style_sheet' );

/**
 * Enqueue theme header javascript.
 */
function wdsbt_enqueue_header_javascript() {

	wp_enqueue_script( 'powder', get_template_directory_uri() . '/assets/js/header.js', array('jquery'), '1.0', true );

}
add_action( 'wp_enqueue_scripts', 'wdsbt_enqueue_header_javascript' );

/**
 * Register block styles.
 */
function wdsbt_register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'minimal' => __( 'Minimal', 'wdsbt' ),
			'text'    => __( 'Text Only', 'wdsbt' )
		),
		'core/columns' => array(
			'column-reverse' => __( 'Reverse', 'wdsbt' ),
		),
		'core/cover' => array(
			'gradient' => __( 'Gradient', 'wdsbt' )
		),
		'core/group' => array(
			'shadow-light' => __( 'Shadow (Light)', 'wdsbt' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'wdsbt' ),
		),
		'core/image' => array(
			'shadow-light' => __( 'Shadow (Light)', 'wdsbt' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'wdsbt' ),
		),
		'core/list' => array(
			'no-style' => __( 'No Style', 'wdsbt' ),
		),
		'core/navigation-link' => array(
			'underline' => __( 'Underline', 'powder' )
		),
		'core/social-links' => array(
			'outline' => __( 'Outline', 'wdsbt' ),
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
add_action( 'init', 'wdsbt_register_block_styles' );

/**
 * Register block pattern categories.
 */
function wdsbt_register_pattern_categories() {

	register_block_pattern_category(
		'content',
		array(
			'label'       => __( 'Content', 'wdsbt' ),
			'description' => __( 'A collection of content patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'hero',
		array(
			'label'       => __( 'Hero', 'wdsbt' ),
			'description' => __( 'A collection of hero patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'page',
		array(
			'label'       => __( 'Pages', 'wdsbt' ),
			'description' => __( 'A collection of page patterns designed for WDS BT.', 'wdsbt' ),
		)
	);
	register_block_pattern_category(
		'template',
		array(
			'label'       => __( 'Templates', 'wdsbt' ),
			'description' => __( 'A collection of template patterns designed for WDS BT.', 'wdsbt' ),
		)
	);

}
add_action( 'init', 'wdsbt_register_pattern_categories' );

/**
 * Register block variations.
 */
function wdsbt_enqueue_block_variations() {
	wp_enqueue_script(
		'wdsbt-enqueue-block-variations',
		get_template_directory_uri() . '/assets/js/variations.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-element', 'wp-primitives' ),
		wp_get_theme()->get( 'Version' ),
		false
	);
}
add_action( 'enqueue_block_editor_assets', 'wdsbt_enqueue_block_variations' );

/**
 * Enqueue custom block stylesheets
 *
 * @return void
 */
function wdsbt_block_stylesheets() {
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
		$handle     = 'wdsbt-' . $block_name . '-style';

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
		$custom_handle     = 'wdsbt-custom-' . $custom_block_name . '-style';

		wp_enqueue_block_style(
			'wdsbt/' . $custom_block_name,
			array(
				'handle' => $custom_handle,
				'src'    => get_parent_theme_file_uri( 'assets/css/blocks/custom/' . $custom_block_name . '.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => $custom_stylesheet,
			)
		);
	}
}
add_action( 'init', 'wdsbt_block_stylesheets' );
