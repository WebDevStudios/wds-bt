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
	foreach ( glob( get_parent_theme_file_path( '/build/blocks/*.css' ) ) as $stylesheet ) {
		$block_name = basename( $stylesheet, '.css' );
		$handle     = 'wdsbt-' . $block_name . '-style';

		wp_enqueue_block_style(
			'core/' . $block_name,
			array(
				'handle' => $handle,
				'src'    => get_parent_theme_file_uri( '/build/blocks/' . $block_name . '.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => $stylesheet,
			)
		);
	}
}

add_filter( 'init', __NAMESPACE__ . '\enqueue_block_stylesheet', 10, 1 );
