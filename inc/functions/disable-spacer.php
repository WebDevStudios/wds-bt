<?php
/**
 * Disable Core Spacer Block
 *
 * @package wds-bt
 */

namespace WebDevStudios\wdsbt;

/**
 * Disable the core spacer block.
 *
 * @return void
 */
function disable_core_spacer() {
	echo 'disable_core_spacer';
	wp_deregister_style( 'wp-block-spacer' );
	unregister_block_type( 'core/spacer' );
}
add_action( 'init', __NAMESPACE__ . '\disable_core_spacer', 9 );
