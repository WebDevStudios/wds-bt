<?php
/**
 * Title: Hero section with graadient cover
 * Slug: powder/hero-gradient-cover
 * Categories: hero
 */
?>
<!-- wp:cover {"dimRatio":50,"minHeight":640,"isDark":false,"align":"full","style":{"spacing":{"blockGap":"10px","margin":{"top":"0"}}},"textColor":"base","className":"is-style-fade-to-black is-style-gradient","layout":{"type":"constrained","wideSize":"1280px"}} -->
<div class="wp-block-cover alignfull is-light is-style-fade-to-black is-style-gradient has-base-color has-text-color" style="margin-top:0;min-height:640px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"constrained","wideSize":"720px","justifyContent":"left"}} -->
	<div class="wp-block-group">
		<!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"300","letterSpacing":"-1px"}},"fontSize":"max-60"} -->
		<h1 class="wp-block-heading has-max-60-font-size" style="font-style:normal;font-weight:300;letter-spacing:-1px"><?php echo esc_html__( 'Are you ready to start designing with WordPress?', 'powder' ); ?></h1>
		<!-- /wp:heading -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:separator -->
			<hr class="wp-block-separator has-alpha-channel-opacity"/>
			<!-- /wp:separator -->
			<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1"}}} -->
			<p style="line-height:1"><?php echo esc_html__( 'Redefine your space with a stylish, modern design.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:buttons {"layout":{"type":"flex"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|medium"}}}} -->
		<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--medium)">
			<!-- wp:button {"backgroundColor":"base","textColor":"contrast","style":{"spacing":{"padding":{"left":"var:preset|spacing|large","right":"var:preset|spacing|large","top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium"}}}} -->
			<div class="wp-block-button"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--large)"><?php echo esc_html__( 'Start Project', 'powder' ); ?></a>
			</div>
			<!-- /wp:button -->
			<!-- wp:button {"style":{"spacing":{"padding":{"left":"var:preset|spacing|large","right":"var:preset|spacing|large","top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium"}}},"className":"is-style-outline"} -->
			<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--large)"><?php echo esc_html__( 'Learn More', 'powder' ); ?></a>
			</div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
