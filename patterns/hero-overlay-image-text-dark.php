<?php
/**
 * Title: Hero section with image and text overlay
 * Slug: powder/hero-overlay-image-text-dark
 * Categories: hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0"},"padding":{"right":"30px","left":"30px","top":"var:preset|spacing|large"}}},"backgroundColor":"contrast","layout":{"type":"constrained","wideSize":"1280px"}} -->
<div class="wp-block-group alignfull has-contrast-background-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--large);padding-right:30px;padding-left:30px">
	<!-- wp:image {"aspectRatio":"16/9","scale":"cover"} -->
	<figure class="wp-block-image"><img alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
	<!-- /wp:image -->
	<!-- wp:group {"className":"is-style-pull-200","layout":{"type":"constrained","wideSize":"800px","justifyContent":"left"}} -->
	<div class="wp-block-group is-style-pull-200">
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","right":"var:preset|spacing|small","bottom":"var:preset|spacing|large"},"margin":{"top":"0"}}},"backgroundColor":"contrast","textColor":"base","className":"is-style-position-relative","layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-position-relative has-base-color has-contrast-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--large)">
			<!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","letterSpacing":"-2px","fontSize":"72px"}}} -->
			<h2 class="wp-block-heading" style="font-size:72px;font-style:normal;font-weight:300;letter-spacing:-2px"><?php echo esc_html__( 'We’re a WordPress design studio based in Laguna Beach, CA.', 'powder' ); ?></h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
