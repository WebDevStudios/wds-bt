<?php
/**
 * Title: Mega footer with text, buttons, social icons
 * Slug: powder/footer-mega
 * Description: Footer with site logo, text, buttons, social icons.
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","right":"30px","left":"30px"},"blockGap":"0px"}},"className":"is-style-pull-100","layout":{"type":"constrained"},"fontSize":"x-small"} -->
<div class="wp-block-group alignfull is-style-pull-100 has-x-small-font-size" style="padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"10px","padding":{"bottom":"var(--wp--preset--spacing--medium)"}}}} -->
<div class="wp-block-group alignwide" style="padding-bottom:var(--wp--preset--spacing--medium)">
<!-- wp:image {"align":"center","id":7,"width":40,"height":40,"sizeSlug":"full","linkDestination":"custom"} -->
<figure class="wp-block-image aligncenter size-full is-resized"><a href="https://powderstudio.com/"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/site-logo-dark.svg'; ?>" alt="Site Logo" class="wp-image-7" width="40" height="40"/></a></figure>
<!-- /wp:image -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php echo esc_html__( 'Made with Powder', 'powder' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"border":{"top":{"color":"#e5e5e5","width":"1px"},"bottom":{"color":"#e5e5e5","width":"1px"}},"spacing":{"padding":{"top":"var(--wp--preset--spacing--medium)","bottom":"var(--wp--preset--spacing--medium)"},"margin":{"top":"0px"}}}} -->
<div class="wp-block-group alignwide" style="border-top-color:#e5e5e5;border-top-width:1px;border-bottom-color:#e5e5e5;border-bottom-width:1px;margin-top:0px;padding-top:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium)">
<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">
<!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%">
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"align":"center"} -->
<div class="wp-block-button aligncenter"><a class="wp-block-button__link wp-element-button" href="#" target="_blank" rel="noreferrer noopener"><?php echo esc_html__( 'Call to Action', 'powder' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
<!-- wp:group {"style":{"border":{"right":{"color":"#e5e5e5"},"left":{"color":"#e5e5e5"}},"spacing":{"blockGap":"0"}}} -->
<div class="wp-block-group" style="border-right-color:#e5e5e5;border-left-color:#e5e5e5">
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php echo esc_html__( '“Just be yourself, there is no one better.”', 'powder' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">—Taylor Swift</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%">
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"align":"center"} -->
<div class="wp-block-button aligncenter"><a class="wp-block-button__link wp-element-button" href="#" target="_blank" rel="noreferrer noopener"><?php echo esc_html__( 'Call to Action', 'powder' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--medium)">
<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:paragraph -->
<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:site-title {"level":0,"isLink":false,"fontSize":"x-small"} /-->
</div>
<!-- /wp:group -->
<!-- wp:paragraph -->
<p> · </p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><a href="https://powderstudio.com/">Theme</a> by <a href="https://briangardner.com/">Brian Gardner</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:social-links {"iconColor":"contrast","iconColorValue":"#000000","size":"has-small-icon-size","style":{"layout":{"selfStretch":"fit","flexSize":null}},"className":"is-style-outline","layout":{"type":"flex","justifyContent":"right"}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-outline">
<!-- wp:social-link {"url":"https://twitter.com/","service":"twitter"} /-->
<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
<!-- wp:social-link {"url":"https://linkedin.com/","service":"linkedin"} /-->
<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
</ul>
<!-- /wp:social-links -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
