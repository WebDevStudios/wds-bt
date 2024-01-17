<?php
/**
 * Title: Section with text, button
 * Slug: powder/hero-text-button
 * Categories: powder-hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}}},"layout":{"type":"constrained","wideSize":"800px"},"metadata":{"name":"Text and Button"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"default"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"max-72"} -->
		<h1 class="wp-block-heading has-text-align-center has-max-72-font-size"><?php echo esc_html__( 'Are you ready to design with WordPress?', 'powder' ); ?></h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html__( 'Redefine your space with a stylish, modern design.', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|x-large","right":"var:preset|spacing|x-large","top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium"}}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--x-large);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--x-large)"><?php echo esc_html__( 'Start Your Project →', 'powder' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
