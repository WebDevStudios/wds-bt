<?php
/**
 * Related Posts Query
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Modify the query variables for the related posts block to exclude the current post.
 * Will show all post types in the same category.
 *
 * @param array    $query_vars The array of query variables.
 * @param WP_Block $block The current block instance being rendered.
 * @return array Modified query variables with the current post excluded.
 */
function related_posts_query( $query_vars, $block ) {

	// Get the current post ID and its categories.
	$current_id = get_the_ID();
	$category   = get_the_category();

	// Initialize variables.
	$cat_id = '';

	// Check if categories are assigned to the post.
	if ( ! empty( $category ) ) {
		if ( function_exists( 'aioseo' ) ) {
			// Get the AIOSEO primary term if available.
			$primary_cat = aioseo()->standalone->primaryTerm->getPrimaryTerm( $current_id, 'category' );

			if ( is_a( $primary_cat, 'WP_Term' ) ) {
				$cat_id = $primary_cat->term_id;
			}
		} elseif ( class_exists( '\WPSEO_Primary_Term' ) ) {
			// Show the post's 'Primary' category if this Yoast feature is available and one is set.
			$primary_term    = new \WPSEO_Primary_Term( 'category', $current_id );
			$primary_term_id = $primary_term->get_primary_term();
			$term            = get_term( $primary_term_id );

			if ( ! is_wp_error( $term ) && $term ) {
				$cat_id = $term->term_id;
			}
		}

		// If no primary category found, include all categories.
		if ( empty( $cat_id ) ) {
			$cat_ids = wp_list_pluck( $category, 'term_id' );
			$cat_id  = implode( ',', $cat_ids );
		}
	}

	// Check if the block has the class 'related-posts-query'.
	if ( isset( $block->parsed_block['attrs']['className'] ) && 'related-posts-query' === $block->parsed_block['attrs']['className'] ) {
		$query_vars['post_type']      = 'any';
		$query_vars['cat']            = $cat_id;
		$query_vars['posts_per_page'] = 3;
		$query_vars['post__not_in']   = array( get_the_ID() );
		$query_vars['orderby']        = 'date';
		$query_vars['order']          = 'desc';
		$query_vars['nopaging']       = true;
		$query_vars['tax_query']      = array(
			'taxonomy' => 'category',
			'field'    => 'id',
			'terms'    => $cat_id,
		);
	}

	// Return the modified query variables.
	return $query_vars;
}
add_filter( 'query_loop_block_query_vars', __NAMESPACE__ . '\related_posts_query', 10, 2 );
