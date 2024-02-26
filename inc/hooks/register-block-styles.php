<?php
/**
 * Register custom block styles.
 * Learn More: https://developer.wordpress.org/reference/functions/register_block_style/
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Register block styles.
 */
function register_block_styles() {

	$block_styles = array(
		'core/button' => array(
			'minimal' => __( 'Minimal', 'wdsbt' ),
			'text'    => __( 'Text Only', 'wdsbt' ),
		),
		'core/quote'  => array(
			'large' => __( 'Large', 'wdsbt' ),
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
add_filter( 'init', __NAMESPACE__ . '\register_block_styles', 10, 1 );
