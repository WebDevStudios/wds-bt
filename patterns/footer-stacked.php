<?php
/**
 * Title: Stacked footer with call to action
 * Slug: powder/footer-stacked
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|small","right":"30px","left":"30px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--small);padding-left:30px">
	<!-- wp:heading {"textAlign":"center","style":{"spacing":{"margin":{"bottom":"20px"}}},"className":"wp-block-heading","fontSize":"max-48"} -->
	<h2 class="wp-block-heading has-text-align-center has-max-48-font-size"><?php echo esc_html__( 'Meet Powder.', 'powder' ); ?></h2>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}}} -->
	<p class="has-text-align-center" style="margin-top:var(--wp--preset--spacing--x-small)"><?php echo esc_html__( 'Our goal is to revolutionize how beautiful WordPress websites are made by embracing the power and flexibility of block-based design.', 'powder' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"},"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|x-large"}}}} -->
	<div class="wp-block-buttons" style="margin-bottom:var(--wp--preset--spacing--x-large)">
		<!-- wp:button -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Call to Action', 'powder' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"},"fontSize":"x-small"} -->
	<div class="wp-block-group has-x-small-font-size">
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
		<p><?php echo esc_html__( 'All Rights Reserved', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
