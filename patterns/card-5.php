<?php
/**
 * Title: Card #5
 * Slug: wdsbt/card-5
 * Categories: Cards
 *
 * @package wdsbt
 */

?>
<!-- wp:group {"metadata":{"name":"Card #5"},"style":{"shadow":"var:preset|shadow|light-400","spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|10","right":"var:preset|spacing|10"}},"border":{"radius":"10px"}},"backgroundColor":"white","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:10px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10);box-shadow:var(--wp--preset--shadow--light-400)">
	<!-- wp:image {"width":"80px","aspectRatio":"1","scale":"cover","className":"is-style-default","style":{"border":{"radius":"6px"}}} -->
	<figure class="wp-block-image is-resized has-custom-border is-style-default"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/Placeholder.jpg" alt="" style="border-radius:6px;aspect-ratio:1;object-fit:cover;width:80px"/></figure>
	<!-- /wp:image -->

<!-- wp:group {"style":{"spacing":{"blockGap":"4px"},"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"default"}} -->
<div class="wp-block-group">
	<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"var:preset|color|grey-600"}}},"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"textColor":"grey-600","fontSize":"xxs","fontFamily":"headline"} -->
	<p class="has-grey-600-color has-text-color has-link-color has-headline-font-family has-xxs-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php esc_html_e( 'Subtitle', 'wdsbt' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:heading {"style":{"typography":{"lineHeight":"1.25"}},"fontSize":"xs"} -->
	<h2 class="wp-block-heading has-xs-font-size" style="line-height:1.25"><?php esc_html_e( 'Title', 'wdsbt' ); ?></h2>
	<!-- /wp:heading -->
</div><!-- /wp:group -->
</div><!-- /wp:group -->