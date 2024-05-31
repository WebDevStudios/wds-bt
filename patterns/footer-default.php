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

<!-- wp:group {"templateLock":"contentOnly","tagName":"footer","metadata":{"name":"Footer"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"className":"alignfull has-black-background-color has-background","layout":{"type":"constrained"}} -->
<footer class="wp-block-group alignfull has-black-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">

	<!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":{"top":"0"}}}} -->
	<div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">

		<!-- wp:column {"width":"66.66%"} -->
		<div class="wp-block-column" style="flex-basis:66.66%">

			<?php echo wp_kses_post( $wds_site_info ); ?>

			<!-- wp:social-links {"iconColor":"black","iconColorValue":"#000","iconBackgroundColor":"white","iconBackgroundColorValue":"#fff","className":"is-style-default"} -->
			<ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default">

				<!-- wp:social-link {"url":"https://www.facebook.com/webdevstudios","service":"facebook"} /-->

				<!-- wp:social-link {"url":"https://twitter.com/webdevstudios","service":"x"} /-->

				<!-- wp:social-link {"url":"https://www.instagram.com/webdevstudios/","service":"instagram"} /-->

				<!-- wp:social-link {"url":"https://www.linkedin.com/company/webdevstudios-llc-","service":"linkedin"} /-->

				<!-- wp:social-link {"url":"https://www.youtube.com/channel/UCh3A6k9S5xKIh6nmKsTk0ag","service":"youtube"} /-->

			</ul>
			<!-- /wp:social-links -->

			<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#f78766"}}}},"textColor":"white"} -->
			<p class="has-white-color has-text-color has-link-color">WDS-BT stands for: <em>WebDevStudios Block Theme</em>. It can be found in the <a href="https://github.com/WebDevStudios/wds-bt">wds-bt github repo.</a>&nbsp; The theme is stood up on WDSLab: <a href="https://wdsbt.wdslab.com">https://wdsbt.wdslab.com</a>&nbsp;</p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"style":{"elements":{"link":{"color":{"text":"#f78766"}}}},"textColor":"white"} -->
			<p class="has-white-color has-text-color has-link-color"><?php echo wp_kses_post( $wds_copyright_info ); ?></p>
			<!-- /wp:paragraph -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"33.33%"} -->
		<div class="wp-block-column" style="flex-basis:33.33%">
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</footer>
<!-- /wp:group -->
