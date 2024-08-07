<?php
/**
 * Title: Default footer
 * Slug: wdsbt/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 *
 * @package wdsbt
 */

// Determine whether to display site logo or site title.
$wds_site_info = has_custom_logo() ? '<!-- wp:site-logo {"width":200} /-->' : '<!-- wp:site-title /--><!-- wp:site-tagline /-->';

// Generate the copyright information.
$wds_copyright_info = esc_html__( 'Copyright &copy; ', 'wdsbt' ) . esc_attr( gmdate( 'Y' ) );
?>

<!-- wp:group {"tagName":"footer","metadata":{"name":"Footer"},"className":"alignfull has-black-background-color has-background","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull has-black-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":{"top":"0"}}}} -->
<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:site-logo {"width":200} /-->

<!-- wp:social-links {"iconColor":"black","iconColorValue":"#000","iconBackgroundColor":"white","iconBackgroundColorValue":"#fff","className":"is-style-default"} -->
<ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default"><!-- wp:social-link {"url":"https://www.facebook.com/webdevstudios","service":"facebook"} /-->

<!-- wp:social-link {"url":"https://twitter.com/webdevstudios","service":"x"} /-->

<!-- wp:social-link {"url":"https://www.instagram.com/webdevstudios/","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://www.linkedin.com/company/webdevstudios-llc-","service":"linkedin"} /-->

<!-- wp:social-link {"url":"https://www.youtube.com/channel/UCh3A6k9S5xKIh6nmKsTk0ag","service":"youtube"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#f78766"}}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color">WDS-BT stands for: <em>WebDevStudios Block Theme</em>. It can be found in the <a href="https://github.com/WebDevStudios/wds-bt">wds-bt github repo.</a>&nbsp; The theme is stood up on WDSLab: <a href="https://wdsbt.wdslab.com">https://wdsbt.wdslab.com</a>&nbsp;</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#f78766"}}}},"textColor":"white"} -->
<p class="has-white-color has-text-color has-link-color">Copyright © 2024</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%"></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></footer>
<!-- /wp:group -->

<!-- wp:cover {"url":"https://wdsbt.local/wp-content/uploads/2024/05/writing-typing-keyboard-technology-white-vintage.jpg","id":738,"dimRatio":50,"customOverlayColor":"#d2d2d3","isDark":false,"layout":{"type":"constrained"}} -->
<div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-background-dim" style="background-color:#d2d2d3"></span><img class="wp-block-cover__image-background wp-image-738" alt="" src="https://wdsbt.local/wp-content/uploads/2024/05/writing-typing-keyboard-technology-white-vintage.jpg" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","placeholder":"Write title…","style":{"elements":{"link":{"color":{"text":"var:preset|color|error-100"}}}},"backgroundColor":"primary-700","textColor":"error-100","fontSize":"large"} -->
<p class="has-text-align-center has-error-100-color has-primary-700-background-color has-text-color has-background has-link-color has-large-font-size">Cover Block</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:cover -->
