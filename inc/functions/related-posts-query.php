<?php
/**
 * Related Posts Query
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Modify the query variables for the related posts block to exclude the current post.
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
		if ( class_exists( '\WPSEO_Primary_Term' ) ) {
			// Show the post's 'Primary' category if this Yoast feature is available and one is set.
			$primary_term    = new \WPSEO_Primary_Term( 'category', $current_id );
			$primary_term_id = $primary_term->get_primary_term();
			$term            = get_term( $primary_term_id );

			if ( is_wp_error( $term ) || ! $term ) {
				// Default to first category (not Yoast) if an error is returned.
				$cat_id = $category[0]->term_id;
			} else {
				// Set variables for cat_display & cat_slug based on Primary Yoast Term.
				$cat_id = $term->term_id;
			}
		} else {
			// Default, display the first category in WP's list of assigned categories.
			$cat_id = $category[0]->term_id;
		}
	}

	// Check if the block has the class 'related-posts-query'.
	if ( isset( $block->parsed_block['attrs']['className'] ) && 'related-posts-query' === $block->parsed_block['attrs']['className'] ) {
		$query_vars['post_type']      = 'post';
		$query_vars['cat']            = $cat_id;
		$query_vars['posts_per_page'] = 3;
		$query_vars['post__not_in']   = array( get_the_ID() );
		$query_vars['orderBy']        = 'date';
		$query_vars['order']          = 'desc';
		$query_vars['nopaging']       = 'true';
	}

	// Return the modified query variables.
	return $query_vars;
}
add_filter( 'query_loop_block_query_vars', __NAMESPACE__ . '\related_posts_query', 10, 2 );
