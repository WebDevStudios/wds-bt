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
$wds_site_info = has_custom_logo() ? '<!-- wp:site-logo {"width":126} /--><!-- wp:site-tagline /-->' : '<!-- wp:site-title /--><!-- wp:site-tagline /-->';

// Generate the copyright information.
$wds_copyright_info = esc_html__( 'Copyright &copy; ', 'wdsbt' ) . esc_attr( gmdate( 'Y' ) );
?>

<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"layout":{"type":"constrained"},"metadata":{"name":"Footer"}} -->
<div class="wp-block-group alignfull" style="margin-top:var(--wp--preset--spacing--30)">

	<!-- wp:group {"style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","orientation":"vertical","flexWrap":"wrap","justifyContent":"stretch"}} -->
	<div class="wp-block-group">

		<!-- wp:group {"metadata":{"name":"Site Info"},"layout":{"type":"flex","orientation":"vertical"}} -->
		<div class="wp-block-group">
			<?php echo wp_kses_post( $wds_site_info ); ?>
		</div>
		<!-- /wp:group -->

		<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"className":"is-style-wide"} -->
		<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
		<!-- /wp:separator -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"metadata":{"name":"Copyright"}} -->
		<div class="wp-block-group">

			<!-- wp:group {"metadata":{"name":"Theme Info"},"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group">

				<!-- wp:paragraph -->
				<p><?php echo wp_kses_post( $wds_copyright_info ); ?></p>
				<!-- /wp:paragraph -->

			</div>
			<!-- /wp:group -->

			<!-- wp:social-links {"size":"has-normal-icon-size","className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"right"}} -->
			<ul class="wp-block-social-links has-normal-icon-size is-style-logos-only">

				<!-- wp:social-link {"url":"#","service":"facebook"} /-->

				<!-- wp:social-link {"url":"#","service":"x"} /-->

				<!-- wp:social-link {"url":"#","service":"github"} /-->

				<!-- wp:social-link {"url":"#","service":"WordPress"} /-->

				<!-- wp:social-link {"url":"#","service":"instagram"} /-->

			</ul>
			<!-- /wp:social-links -->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
