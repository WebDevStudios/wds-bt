<?php
/**
 * Title: Call to action section with text, button
 * Slug: powder/content-cta-stacked-dark
 * Categories: powder-content
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"},"metadata":{"name":"Call to Action"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained","wideSize":"1080px","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"400","lineHeight":"1"}},"fontSize":"x-small"} -->
			<p class="has-x-small-font-size" style="font-style:normal;font-weight:400;line-height:1;text-transform:uppercase"><?php echo esc_html__( 'Meet Powder', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:separator -->
			<hr class="wp-block-separator has-alpha-channel-opacity"/>
			<!-- /wp:separator -->
		</div>
		<!-- /wp:group -->
		<!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"300","letterSpacing":"-0.5px"}},"fontSize":"max-48"} -->
		<h2 class="wp-block-heading has-max-48-font-size" style="font-style:normal;font-weight:300;letter-spacing:-0.5px"><?php echo esc_html__( 'We want to revolutionize how WordPress websites are made by embracing block-based design.', 'powder' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:buttons -->
		<div class="wp-block-buttons">
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}},"fontSize":"tiny"} -->
			<div class="wp-block-button has-custom-font-size has-tiny-font-size"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" href="#"><?php echo esc_html__( 'Learn More →', 'powder' ); ?></a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
