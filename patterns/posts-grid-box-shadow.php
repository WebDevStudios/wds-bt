<?php
/**
 * Title: Grid of posts in three columns with box shadow
 * Slug: powder/posts-grid-box-shadow
 * Categories: posts
 * Block Types: core/query
 */
?>
<!-- wp:query {"queryId":0,"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:group {"className":"is-style-shadow-light","layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-shadow-light">
			<!-- wp:group {"layout":{"type":"default"}} -->
			<div class="wp-block-group">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"16/9"} /-->
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px","padding":{"top":"var:preset|spacing|small","right":"var:preset|spacing|small","bottom":"var:preset|spacing|small","left":"var:preset|spacing|small"},"margin":{"top":"0"}}}} -->
					<div class="wp-block-group" style="margin-top:0;padding-top:var(--wp--preset--spacing--small);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--small);padding-left:var(--wp--preset--spacing--small)">
					<!-- wp:group {"tagName":"header","style":{"spacing":{"blockGap":"10px"}},"className":"entry-header","layout":{"type":"default"}} -->
					<header class="wp-block-group entry-header">
						<!-- wp:post-title {"isLink":true,"fontSize":"large"} /-->
						<!-- wp:pattern {"slug":"powder/post-meta"} /-->
					</header>
					<!-- /wp:group -->
					<!-- wp:post-excerpt {"moreText":"Read More →","excerptLength":20,"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}},"fontSize":"small"} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	<!-- /wp:post-template -->
	<!-- wp:query-pagination {"paginationArrow":"arrow","layout":{"type":"flex","justifyContent":"center"}} -->
		<!-- wp:query-pagination-previous /-->
		<!-- wp:query-pagination-next /-->
	<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
