<?php
/**
 * Title: Comments
 * Slug: powder/comments
 * Inserter: no
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--medium)">
	<!-- wp:comments {"className":"wp-block-comments-query-loop "} -->
	<div class="wp-block-comments wp-block-comments-query-loop">
		<!-- wp:comments-title {"level":3} /-->
		<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|medium"}}}} -->
		<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--medium)">
			<!-- wp:comment-template -->
			<!-- wp:columns {"style":{"spacing":{"blockGap":"10px","margin":{"bottom":"30px"}}}} -->
				<div class="wp-block-columns" style="margin-bottom:30px">
				<!-- wp:column {"width":"48px"} -->
				<div class="wp-block-column" style="flex-basis:48px">
					<!-- wp:avatar {"size":48,"style":{"border":{"radius":"24px"}}} /-->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}}} -->
				<div class="wp-block-column">
					<!-- wp:comment-author-name /-->
					<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":"10px"}},"layout":{"type":"flex"}} -->
					<div class="wp-block-group" style="margin-top:0px">
						<!-- wp:comment-date /-->
						<!-- wp:comment-edit-link /-->
					</div>
					<!-- /wp:group -->
					<!-- wp:comment-content /-->
					<!-- wp:comment-reply-link /-->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
			<!-- /wp:comment-template -->
		</div>
		<!-- /wp:group -->
		<!-- wp:comments-pagination -->
			<!-- wp:comments-pagination-previous /-->
			<!-- wp:comments-pagination-numbers /-->
			<!-- wp:comments-pagination-next /-->
		<!-- /wp:comments-pagination -->
		<!-- wp:post-comments-form /-->
	</div>
	<!-- /wp:comments -->
</div>
<!-- /wp:group -->
