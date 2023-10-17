<?php
/**
 * Title: Hero section with text and image
 * Slug: powder/hero-stacked-text-image
 * Categories: hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"blockGap":"0px","padding":{"top":"var:preset|spacing|medium"},"margin":{"top":"0"}}},"layout":{"type":"constrained","wideSize":"800px"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--medium)">
	<!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"96px","letterSpacing":"-2px","fontStyle":"normal","fontWeight":"200"}},"className":"wp-block-heading"} -->
	<h1 class="wp-block-heading has-text-align-center" style="font-size:96px;font-style:normal;font-weight:200;letter-spacing:-2px"><?php echo esc_html__( 'Ellie Nash', 'powder' ); ?></h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","style":{"typography":{"textTransform":"uppercase"},"spacing":{"margin":{"bottom":"var:preset|spacing|medium"}}}} -->
	<p class="has-text-align-center" style="margin-bottom:var(--wp--preset--spacing--medium);text-transform:uppercase"><?php echo esc_html__( 'Website + Brand Design', 'powder' ); ?></p>
	<!-- /wp:paragraph -->
	<!-- wp:image {"aspectRatio":"1","scale":"cover"} -->
	<figure class="wp-block-image"><img alt="" style="aspect-ratio:1;object-fit:cover"/></figure>
	<!-- /wp:image -->
</div>
<!-- /wp:group -->