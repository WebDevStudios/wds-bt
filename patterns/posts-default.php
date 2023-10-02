<?php
/**
 * Title: List of posts in one column
 * Slug: powder/posts-default
 * Categories: posts
 * Block Types: core/query
 */
?>
<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true},"layout":{"type":"constrained"}} -->
<div class="wp-block-query">
	<!-- wp:post-template -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}}} -->
		<div class="wp-block-group">
			<!-- wp:post-title {"isLink":true} /-->
			<!-- wp:pattern {"slug":"powder/post-meta"} /-->
		</div>
		<!-- /wp:group -->
		<!-- wp:post-excerpt {"moreText":"Read More"} /-->
	<!-- /wp:post-template -->
	<!-- wp:query-pagination -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
