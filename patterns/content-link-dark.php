<?php
/**
 * Title: Section with image, links, social icons
 * Slug: powder/content-link-dark
 * Categories: powder-content
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}},"dimensions":{"minHeight":"100vh"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"},"metadata":{"name":"Link Page"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="min-height:100vh;margin-top:0px;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large)">
	<!-- wp:group {"style":{"spacing":{"padding":{"right":"30px","left":"30px"}}},"layout":{"type":"constrained","wideSize":"600px"}} -->
	<div class="wp-block-group" style="padding-right:30px;padding-left:30px">
		<!-- wp:image {"align":"center","width":100,"height":100,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
		<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/sample-avatar-light.png'; ?>" alt="Sample avatar" style="object-fit:cover;width:100px;height:100px" width="100" height="100"/></figure>
		<!-- /wp:image -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"5px","margin":{"top":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--x-small)">
			<!-- wp:heading {"textAlign":"center","level":1,"className":"wp-block-heading","fontSize":"x-large"} -->
			<h1 class="wp-block-heading has-text-align-center has-x-large-font-size"><?php echo esc_html__( 'Your Name', 'powder' ); ?></h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size"><?php echo esc_html__( 'A little something about yourself.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Visit My Website', 'powder' ); ?></a></div>
			<!-- /wp:button -->
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Shop My Store', 'powder' ); ?></a></div>
			<!-- /wp:button -->
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'View My Services', 'powder' ); ?></a></div>
			<!-- /wp:button -->
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
			<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Read My Journal', 'powder' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
		<!-- wp:social-links {"iconColor":"base","iconColorValue":"#ffffff","size":"has-normal-icon-size","align":"center","className":"has-icon-base-color is-style-outline","layout":{"type":"flex","justifyContent":"center"}} -->
		<ul class="wp-block-social-links aligncenter has-normal-icon-size has-icon-color has-icon-base-color is-style-outline">
			<!-- wp:social-link {"url":"https://twitter.com/","service":"x"} /-->
			<!-- wp:social-link {"url":"https://www.linkedin.com/","service":"linkedin"} /-->
			<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
			<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
			<!-- wp:social-link {"url":"https://www.threads.net/","service":"threads"} /-->
		</ul>
		<!-- /wp:social-links -->
		<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|medium"}}},"fontSize":"x-small"} -->
		<p class="has-text-align-center has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--medium)"><a href="https://powderstudio.com/">Made with Powder</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
