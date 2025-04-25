<?php
/**
 * Title: Card #11
 * Slug: wdsbt/card-11
 * Categories: Cards
 *
 * @package wdsbt
 */

?>
<!-- wp:group {"metadata":{"name":"Card #11","categories":["Cards"],"patternName":"wdsbt/card-11"},"style":{"shadow":"var:preset|shadow|light-400","spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"},"blockGap":"0"},"border":{"radius":"10px"}},"backgroundColor":"white","layout":{"type":"default"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:10px;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;box-shadow:var(--wp--preset--shadow--light-400)"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/2","sizeSlug":"large","overlayColor":"black-alpha-50","dimRatio":100,"style":{"border":{"radius":{"topLeft":"10px","topRight":"10px"}}}} /-->

<!-- wp:group {"style":{"layout":{"selfStretch":"fit","flexSize":null},"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|40","left":"var:preset|spacing|30","right":"var:preset|spacing|30"},"blockGap":"0.5rem"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--30)"><!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-400"}}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"grey-400","fontSize":"xxxs","fontFamily":"headline"} -->
<p class="has-grey-400-color has-text-color has-link-color has-headline-font-family has-xxxs-font-size" style="font-style:normal;font-weight:500"><?php esc_html_e( 'Subtitle', 'wdsbt' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|black"}}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"black","fontSize":"l"} /-->

<!-- wp:post-excerpt {"showMoreOnNewLine":false,"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-500"}}}},"textColor":"grey-500"} /--></div>
<!-- /wp:group -->

<!-- wp:separator {"className":"is-style-wide","backgroundColor":"grey-100"} -->
<hr class="wp-block-separator has-text-color has-grey-100-color has-alpha-channel-opacity has-grey-100-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}}},"fontSize":"xxs","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group has-xxs-font-size" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:paragraph -->
<p>
<?php
/* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */
printf( esc_html__( '%1$sLink text%2$s', 'wdsbt' ), '<a href="' . esc_url( '#' ) . '">', '</a>' );
?>
</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>
<?php
/* Translators: 1. is the start of a 'a' HTML element, 2. is the end of a 'a' HTML element */
printf( esc_html__( '%1$sLink text%2$s', 'wdsbt' ), '<a href="' . esc_url( '#' ) . '">', '</a>' );
?>
</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->