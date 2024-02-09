<?php
/**
 * Title: Pricing section for single product
 * Slug: powder/pricing-single
 * Categories: powder-pricing
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}}},"layout":{"type":"constrained"},"metadata":{"name":"Pricing"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"blockGap":{"top":"0","left":"0"},"padding":{"top":"0","bottom":"0","left":"0","right":"0"}},"color":{"background":"#f5f5f5"}}} -->
	<div class="wp-block-columns alignwide are-vertically-aligned-center has-background" style="background-color:#f5f5f5;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
		<!-- wp:column {"verticalAlignment":"center","width":"65%","style":{"spacing":{"padding":{"right":"var:preset|spacing|large","left":"var:preset|spacing|large","top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}}}} -->
		<div class="wp-block-column is-vertically-aligned-center" style="padding-top:var(--wp--preset--spacing--large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large);padding-left:var(--wp--preset--spacing--large);flex-basis:65%">
			<!-- wp:heading {"style":{"typography":{"fontSize":"96px","fontStyle":"normal","fontWeight":"700","lineHeight":"1","letterSpacing":"-1px"}}} -->
			<h2 class="wp-block-heading" style="font-size:96px;font-style:normal;font-weight:700;letter-spacing:-1px;line-height:1"><?php echo esc_html__( 'Unleash your inner designer.', 'powder' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:group {"layout":{"type":"constrained","wideSize":"600px","justifyContent":"left"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.25"}},"fontSize":"max-30"} -->
				<p class="has-max-30-font-size" style="line-height:1.25"><?php echo esc_html__( 'One theme. Infinite design possibilities. Cancel or pause subscription anytime. Money-back guarantee. No hassle.', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"center","width":"35%","style":{"spacing":{"padding":{"right":"var:preset|spacing|large","left":"var:preset|spacing|large","top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"contrast","textColor":"base"} -->
		<div class="wp-block-column is-vertically-aligned-center has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large);padding-left:var(--wp--preset--spacing--large);flex-basis:35%">
			<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"700","lineHeight":"1.25"}},"fontSize":"max-72"} -->
			<p class="has-text-align-center has-max-72-font-size" style="font-style:normal;font-weight:700;line-height:1.25">$295</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"0"}},"typography":{"textTransform":"uppercase","lineHeight":"1.5"}},"fontSize":"x-small"} -->
			<p class="has-text-align-center has-x-small-font-size" style="margin-top:0;line-height:1.5;text-transform:uppercase">/ <?php echo esc_html__( 'per year', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.25"},"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}},"fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size" style="margin-top:var(--wp--preset--spacing--x-small);line-height:1.25"><?php echo esc_html__( 'Perfect for WordPress freelancers who wish to scale their business.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
			<div class="wp-block-buttons">
				<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"spacing":{"padding":{"left":"var:preset|spacing|large","right":"var:preset|spacing|large","top":"var:preset|spacing|small","bottom":"var:preset|spacing|small"}}},"fontSize":"small"} -->
				<div class="wp-block-button has-custom-font-size has-small-font-size"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:var(--wp--preset--spacing--small);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--small);padding-left:var(--wp--preset--spacing--large)"><?php echo esc_html__( 'Get Professional →', 'powder' ); ?></a>
				</div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
