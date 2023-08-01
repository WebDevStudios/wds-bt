<?php
/**
 * Title: Hero section with text and image.
 * Slug: powder/hero-text-image-dark
 * Description: Hero section with text, heading, image.
 * Categories: hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"blockGap":"0px","padding":{"top":"var:preset|spacing|medium"},"margin":{"top":"0"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained","wideSize":"800px"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--medium)">
	<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"96px","letterSpacing":"-2px","fontStyle":"normal","fontWeight":"200"}},"className":"wp-block-heading"} -->
	<h1 class="wp-block-heading has-text-align-center" style="font-size:96px;font-style:normal;font-weight:200;letter-spacing:-2px"><?php echo esc_html__( 'Ellie Nash', 'powder' ); ?></h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"bottom":"var:preset|spacing|medium"}}}} -->
	<p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--medium);text-transform:uppercase"><?php echo esc_html__( 'Website + Brand Design', 'powder' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:image {"align":"center","sizeSlug":"full","linkDestination":"none"} -->
	<figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/sample-1600x1600-light.png'; ?>" alt="Sample image"/></figure>
	<!-- /wp:image -->
</div>
<!-- /wp:group -->