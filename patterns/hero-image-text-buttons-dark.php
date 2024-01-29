<?php
/**
 * Title: Section with image, text, buttons
 * Slug: powder/hero-image-text-buttons-dark
 * Categories: powder-hero
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained","wideSize":"960px"},"metadata":{"name":"Image and Text"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:image {"id":873,"aspectRatio":"16/9","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
	<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/sample-image-light.png'; ?>" alt="Sample image" class="wp-image-873" style="aspect-ratio:16/9;object-fit:cover"/></figure>
	<!-- /wp:image -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small","margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--medium)">
		<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"max-48"} -->
		<h1 class="wp-block-heading has-text-align-center has-max-48-font-size"><?php echo esc_html__( 'Meet Powder.', 'powder' ); ?></h1>
		<!-- /wp:heading -->
		<!-- wp:paragraph {"align":"center"} -->
		<p class="has-text-align-center"><?php echo esc_html__( 'Our goal is to revolutionize how beautiful WordPress websites are made by embracing the power and flexibility of block-based design.', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" href="#"><?php echo esc_html__( 'Call to Action', 'powder' ); ?></a></div>
		<!-- /wp:button -->
		<!-- wp:button {"className":"is-style-outline"} -->
		<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#"><?php echo esc_html__( 'Learn More', 'powder' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
