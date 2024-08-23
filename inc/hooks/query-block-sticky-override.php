<?php
/**
 * Query Block functions.
 *
 * Override WP Query to respect perPage count even with sticky posts.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Adds a custom query variable to indicate whether sticky posts should be ignored.
 *
 * This function adds 'ignore_sticky_posts' to the list of recognized query variables.
 *
 * @param array $query_vars An array of query variables.
 * @return array The modified array of query variables.
 */
function add_query_var( $query_vars ) {
	$query_vars[] = 'ignore_sticky_posts';
	return $query_vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\add_query_var' );

/**
 * Sets the 'ignore_sticky_posts' query variable.
 *
 * This function forces the 'ignore_sticky_posts' query variable to true,
 * ensuring that sticky posts are always ignored in the main query.
 *
 * @param \WP_Query $query The WP_Query instance (passed by reference).
 */
function parse_query_var( $query ) {
	// Check if ignoring sticky posts is requested.
	$query->query_vars['ignore_sticky_posts'] = isset( $query->query_vars['ignore_sticky_posts'] ) && $query->query_vars['ignore_sticky_posts'];
	$query->query_vars['ignore_sticky_posts'] = true;
}
add_filter( 'parse_query', __NAMESPACE__ . '\parse_query_var' );

/**
 * Modifies the query clauses to handle sticky posts without including them in the main results.
 *
 * This function alters the WHERE and ORDER BY clauses of the query to ensure that
 * sticky posts are included in a specific order without affecting the main result set.
 *
 * @param  array     $clauses An associative array of the query clauses.
 * @param \WP_Query $query The WP_Query instance.
 * @return array The modified query clauses.
 */
function modify_posts_clauses( $clauses, $query ) {
	global $wpdb;

	if ( ! $query->query_vars['post__in'] && $query->is_home && ! $query->query_vars['ignore_sticky_posts'] ) {
		$sticky_posts = get_option( 'sticky_posts' );

		// Retrieve published sticky posts of the desired post type(s).
		$stickies_query = new \WP_Query(
			array(
				'post__in'       => $sticky_posts,
				'post_type'      => $query->query_vars['post_type'],
				'post_status'    => 'publish',
				'fields'         => 'ids',
				'posts_per_page' => count( $sticky_posts ),
			)
		);

		$queried_stickies = $stickies_query->posts;

		if ( ! empty( $queried_stickies ) ) {
			// Generate the WHERE clause to exclude sticky posts.
			$post__in_sticky  = implode( ',', array_map( 'absint', $queried_stickies ) );
			$clauses['where'] = 'AND ((1=1 ' . $clauses['where'] . ") OR {$wpdb->posts}.ID IN ($post__in_sticky))";
			$sticky_orderby   = "FIELD( {$wpdb->posts}.ID, $post__in_sticky ) DESC";

			// Add the sticky posts handling to the ORDER BY clause.
			if ( $clauses['orderby'] ) {
				$clauses['orderby'] = $sticky_orderby . ', ' . $clauses['orderby'];
			} else {
				$clauses['orderby'] = $sticky_orderby;
			}
		}
	}

	return $clauses;
}
add_filter( 'posts_clauses', __NAMESPACE__ . '\modify_posts_clauses', 10, 2 );
