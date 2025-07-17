<?php
/**
 * Title: Comments
 * Slug: wdsbt/comments
 * Categories: content
 * Block Types: custom/comments
 * Inserter: false
 *
 * @package wdsbt
 */

?>
<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">

	<!-- wp:spacer {"height":"var:preset|spacing|40"} -->
	<div style="height:var(--wp--preset--spacing--40)" aria-hidden="true" class="wp-block-spacer">
	</div>
	<!-- /wp:spacer -->

	<!-- wp:comments -->
	<div class="wp-block-comments">
		<!-- wp:heading {"level":2} -->
		<h2><?php echo esc_html_x( 'Comments', 'Title of comments section', 'wdsbt' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:comment-template -->
			<!-- wp:columns -->
			<div class="wp-block-columns">
				<!-- wp:column {"width":"40px"} -->
				<div class="wp-block-column" style="flex-basis:40px">
					<!-- wp:avatar {"size":40,"style":{"border":{"radius":"20px"}}} /-->
				</div>
				<!-- /wp:column -->

				<!-- wp:column -->
				<div class="wp-block-column">
					<!-- wp:comment-author-name {"fontSize":"small"} /-->

					<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"layout":{"type":"flex"}} -->
					<div class="wp-block-group">
						<!-- wp:comment-date {"fontSize":"small"} /-->
						<!-- wp:comment-edit-link {"fontSize":"small"} /-->
					</div>
					<!-- /wp:group -->

					<!-- wp:comment-content /-->
					<!-- wp:comment-reply-link {"fontSize":"small"} /-->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		<!-- /wp:comment-template -->

		<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
			<!-- wp:comments-pagination-previous /-->
			<!-- wp:comments-pagination-numbers /-->
			<!-- wp:comments-pagination-next /-->
		<!-- /wp:comments-pagination -->

		<!-- wp:post-comments-form /-->
	</div>
	<!-- /wp:comments -->

</div>
<!-- /wp:group -->
