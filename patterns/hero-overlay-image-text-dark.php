<?php
/**
 * Title: Hero section with image and text overlay.
 * Slug: powder/hero-overlay-image-text-dark
 * Description: Hero section with image, heading.
 * Categories: hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0"},"padding":{"right":"30px","left":"30px","top":"var:preset|spacing|large"}}},"backgroundColor":"contrast","layout":{"type":"constrained","wideSize":"1280px"}} -->
<div class="wp-block-group alignfull has-contrast-background-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--large);padding-right:30px;padding-left:30px">
	<!-- wp:image {"align":"wide","aspectRatio":"16/9","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
	<figure class="wp-block-image alignwide size-full"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/sample-image-light.png'; ?>" alt="Sample image" style="aspect-ratio:16/9;object-fit:cover"/></figure>
	<!-- /wp:image -->
	<!-- wp:group {"style":{"spacing":{"margin":{"top":"0"}}},"className":"is-style-pull-200","layout":{"type":"constrained","wideSize":"800px","justifyContent":"left"}} -->
	<div class="wp-block-group is-style-pull-200" style="margin-top:0">
		<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|large","right":"var:preset|spacing|small","bottom":"var:preset|spacing|large"},"margin":{"top":"0"}}},"backgroundColor":"contrast","textColor":"base","className":"is-style-position-relative","layout":{"type":"default"}} -->
		<div class="wp-block-group is-style-position-relative has-base-color has-contrast-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--large)">
			<!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","letterSpacing":"-2px"}},"fontSize":"max-72"} -->
			<h2 class="wp-block-heading has-max-72-font-size" style="font-style:normal;font-weight:300;letter-spacing:-2px"><?php echo esc_html__( 'We’re a WordPress design studio based in Laguna Beach, CA.', 'powder' ); ?></h2>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
