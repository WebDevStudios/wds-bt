<?php
/**
 * Title: Grid of posts, 3 columns
 * Slug: powder/posts-grid
 * Categories: posts
 * Block Types: core/query
 */
?>
<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} -->
		<article class="wp-block-group">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small","margin":{"top":"30px"}}}} -->
			<div class="wp-block-group" style="margin-top:30px">
				<!-- wp:group {"tagName":"header","style":{"spacing":{"blockGap":"10px"}},"className":"entry-header","layout":{"type":"default"}} -->
				<header class="wp-block-group entry-header">
					<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
					<!-- wp:pattern {"slug":"powder/post-meta"} /-->
				</header>
				<!-- /wp:group -->
				<!-- wp:post-excerpt {"moreText":"Read More →","excerptLength":20,"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}},"fontSize":"small"} /-->
			</div>
			<!-- /wp:group -->
		</article>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
