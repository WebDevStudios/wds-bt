<?php
/**
 * Title: Related Posts
 * Slug: wdsbt/related-posts
 * Categories: posts
 * Block Types: custom/related-posts
 * Inserter: false
 *
 * @package wdsbt
 */

?>

<!-- wp:group {"queryId":8,"tagName":"aside","metadata":{"name":"Recent Posts"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40"},"padding":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<aside class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--50)">

	<!-- wp:query {"queryId":0,"query":{}} -->
	<div class="wp-block-query">

		<!-- wp:pattern {"slug":"wdsbt/primary-category"} /-->

		<!-- wp:post-template {"className":"related-posts-query","layout":{"type":"grid","columnCount":3}} -->
			<!-- wp:post-featured-image {"isLink":true} /-->
			<!-- wp:post-terms {"term":"category"} /-->
			<!-- wp:post-title {"isLink":true,"fontSize":"small"} /-->
			<!-- wp:read-more {"content":""} /-->
		<!-- /wp:post-template -->

	</div>
	<!-- /wp:query -->

</aside>
<!-- /wp:group -->
