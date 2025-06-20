<?php
/**
 * Register custom block category(ies).
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Register_wds_category
 *
 * @param array $categories block categories.
 * @return array $categories block categories.
 * @author WebDevStudios
 */
function register_wds_category( $categories ) {
	$custom_block_category = array(
		'slug'  => __( 'wds-blocks-category', 'wdsbt' ),
		'title' => __( 'WDS Blocks', 'wdsbt' ),
	);

	$categories_sorted    = array();
	$categories_sorted[0] = $custom_block_category;

	foreach ( $categories as $category ) {
		$categories_sorted[] = $category;
	}

	return $categories_sorted;
}

add_filter( 'block_categories_all', __NAMESPACE__ . '\register_wds_category', 10, 1 );
