<?php
/**
 * Title: Header with site logo, social icons
 * Slug: powder/header-logo-social-dark
 * Description: Header with site logo, site title, navigation, social icons.
 * Categories: header
 * Block Types: core/template-part/header
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"backgroundColor":"contrast","textColor":"base","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background has-link-color" style="margin-top:0px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px">
<!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide">
<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"layout":{"selfStretch":"fixed","flexSize":"200px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group">
<!-- wp:site-logo {"width":30} /-->
<!-- wp:site-title {"style":{"typography":{"textTransform":"uppercase"}},"fontSize":"medium"} /-->
</div>
<!-- /wp:group -->
<!-- wp:navigation {"layout":{"type":"flex","setCascadingProperties":true},"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}}} /-->
<!-- wp:social-links {"size":"has-small-icon-size","style":{"layout":{"selfStretch":"fixed","flexSize":"200px"}},"className":"is-style-outline is-style-hidden-mobile","layout":{"type":"flex","justifyContent":"right"}} -->
<ul class="wp-block-social-links has-small-icon-size is-style-outline is-style-hidden-mobile">
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
