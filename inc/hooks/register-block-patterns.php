<?php
/**
 * Registers custom block pattern categories for the WDS BT theme.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers custom block pattern for the WDS BT theme.
 */
function register_custom_block_pattern() {

		register_block_pattern(
			'wdsbt/pattern-name',
			array(
				'title'         => __( 'Pattern Title', 'wdsbt' ),
				'blockTypes'    => array( 'core/query' ),
				'templateTypes' => array( 'single-post' ),
				'postTypes'     => array( '' ),
				'description'   => _x( 'Block Pattern Name', 'Block pattern description', 'wdsbt' ),
				'content'       => '',
			)
		);
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern', 9 );
