<?php
/**
 * Add home link block to menu.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Add home link block to menu.
 *
 * This function checks if the provided context is a Navigation menu and if the Navigation menu already contains the home link block.
 *
 * If the conditions are met, it adds the home link block to the navigation block array.
 *
 * @param array  $hooked_block_types Array of block types hooked to be displayed.
 * @param string $relative_position The relative position of the block.
 * @param string $anchor_block_type The type of the anchor block.
 * @param mixed  $context The context in which the block is being added.
 *
 * @return array Modified array of hooked block types.
 */
function add_home_block_to_navigation_block( $hooked_block_types, $relative_position, $anchor_block_type, $context ) {

	// Is $context a Navigation menu?
	if ( ! $context instanceof WP_Post || 'wp_navigation' !== $context->post_type ) {
		return $hooked_block_types;
	}

	// Does the Navigation menu already contain the block?
	if ( str_contains( $context->post_content, '<!-- wp:home-link {"label":"Home"}' ) ) {
		return $hooked_block_types;
	}

	if ( 'first_child' === $relative_position && 'core/navigation' === $anchor_block_type ) {
		$hooked_block_types[] = 'core/home-link';
	}
	return $hooked_block_types;
}

add_filter( 'hooked_block_types', __NAMESPACE__ . '\add_home_block_to_navigation_block', 10, 4 );
