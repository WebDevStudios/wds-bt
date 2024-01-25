<?php
/**
 * Register custom block styles.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Register block styles.
 */
function register_block_styles() {

	$block_styles = array(
		'core/button'       => array(
			'minimal' => __( 'Minimal', 'wdsbt' ),
			'text'    => __( 'Text Only', 'wdsbt' ),
		),
		'core/columns'      => array(
			'column-reverse' => __( 'Reverse', 'wdsbt' ),
		),
		'core/cover'        => array(
			'gradient' => __( 'Gradient', 'wdsbt' ),
		),
		'core/group'        => array(
			'shadow-light' => __( 'Shadow (Light)', 'wdsbt' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'wdsbt' ),
		),
		'core/image'        => array(
			'shadow-light' => __( 'Shadow (Light)', 'wdsbt' ),
			'shadow-solid' => __( 'Shadow (Solid)', 'wdsbt' ),
		),
		'core/list'         => array(
			'no-style' => __( 'No Style', 'wdsbt' ),
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
add_filter( 'init', __NAMESPACE__ . '\register_block_styles', 10, 1 );
