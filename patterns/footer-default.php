<?php
/**
 * Title: Default footer
 * Slug: wdsbt/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 *
 * @package wdsbt
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"layout":{"type":"constrained"},"fontSize":"x-small"} -->
<div class="wp-block-group alignfull has-x-small-font-size" style="margin-top:0;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"fontSize":"x-small"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:paragraph -->
			<p> · </p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p>Theme by <a href="https://webdevstudios.com/">WebDevStudios</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:paragraph -->
		<p><a href="https://twitter.com/">Twitter</a> · <a href="https://www.linkedin.com/">LinkedIn</a> · <a href="https://instagram.com/">Instagram</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
