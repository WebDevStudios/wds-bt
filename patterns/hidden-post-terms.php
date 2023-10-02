<?php
/**
 * Title: Post terms
 * Slug: powder/post-terms
 * Inserter: no
 */
?>
<!-- wp:group {"layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"0px"}}} -->
<div class="wp-block-group">
	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"5px"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph -->
		<p><?php echo esc_html__( 'In:', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:post-terms {"term":"category"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"style":{"spacing":{"blockGap":"5px"}}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph -->
		<p><?php echo esc_html__( 'Tags:', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
		<!-- wp:post-terms {"term":"post_tag"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
