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
				'content'       => '<!-- wp:group {"tagName":"aside","metadata":{"name":"Related Posts"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"},"padding":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} --><aside class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)"><!-- wp:query {"queryId":0,"query":{"perPage":"2","pages":0,"offset":0,"inherit":false},"className":"related-posts"} --><div class="wp-block-query related-posts"><!-- wp:post-template {"className":"related-posts-query","layout":{"type":"grid","columnCount":null,"minimumColumnWidth":"22rem"}} --><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /--><!-- wp:post-terms {"term":"category"} /--><!-- wp:post-title {"isLink":true,"fontSize":"small"} /--><!-- wp:post-excerpt {"excerptLength":20} /--><!-- wp:read-more /--><!-- /wp:post-template --></div><!-- /wp:query --></aside><!-- /wp:group -->',
			)
		);
}
add_action( 'init', __NAMESPACE__ . '\register_custom_block_pattern', 9 );
