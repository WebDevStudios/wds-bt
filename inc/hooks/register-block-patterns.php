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
			'wdsbt/related-posts',
			array(
				'title'         => __( 'Related Posts', 'wdsbt' ),
				'blockTypes'    => array( 'core/query' ),
				'templateTypes' => array( 'single-post' ),
				'postTypes'     => array( '' ),
				'description'   => _x( 'Related Posts query variation', 'Block pattern description', 'wdsbt' ),
				'content'       => "<!-- wp:group {\"tagName\":\"aside\",\"metadata\":{\"name\":\"Recent Posts\"},\"style\":{\"spacing\":{\"margin\":{\"top\":\"var:preset|spacing|40\"},\"padding\":{\"bottom\":\"var:preset|spacing|50\"}}},\"layout\":{\"type\":\"constrained\"}} -->\n<aside class=\"wp-block-group\" style=\"margin-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)\"><!-- wp:query {\"queryId\":0,\"query\":{\"perPage\":10,\"pages\":0,\"offset\":0,\"postType\":\"post\",\"order\":\"desc\",\"orderBy\":\"date\",\"author\":\"\",\"search\":\"\",\"exclude\":[],\"sticky\":\"\",\"inherit\":true,\"taxQuery\":null,\"parents\":[]}} -->\n<div class=\"wp-block-query\"><!-- wp:post-template {\"className\":\"related-posts-query\",\"layout\":{\"type\":\"grid\",\"columnCount\":3}} -->\n<!-- wp:post-featured-image {\"isLink\":true} /-->\n\n<!-- wp:post-terms {\"term\":\"category\"} /-->\n\n<!-- wp:post-title {\"isLink\":true,\"fontSize\":\"small\"} /-->\n\n<!-- wp:read-more {\"content\":\"\"} /-->\n<!-- /wp:post-template --></div>\n<!-- /wp:query --></aside>\n<!-- /wp:group -->",
			)
		);
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern', 9 );
