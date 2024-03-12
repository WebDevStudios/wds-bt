<?php
/**
 * Title: Default footer
 * Slug: wdsbt/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 *
 * @package wdsbt
 */

?>

<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"var:preset|spacing|30"}}},"layout":{"type":"constrained"},"metadata":{"name":"Footer"}} -->
<div class="wp-block-group alignfull" style="margin-top:var(--wp--preset--spacing--30)">

	<!-- wp:group {"style":{"spacing":{"blockGap":"0px"}},"layout":{"type":"flex","orientation":"vertical","flexWrap":"wrap","justifyContent":"stretch"}} -->
	<div class="wp-block-group">

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"},"metadata":{"name":"Site Info"}} -->
		<div class="wp-block-group">

			<!-- wp:site-logo {"width":126} /-->

			<!-- wp:site-tagline /-->

		</div>
		<!-- /wp:group -->

		<!-- wp:separator {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"className":"is-style-wide"} -->
		<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
		<!-- /wp:separator -->

		<!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"metadata":{"name":"Copyright"}} -->
		<div class="wp-block-group">

			<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"wrap"}} -->
			<div class="wp-block-group">

				<!-- wp:paragraph -->
				<p> © 2024. Theme by <a href="https://webdevstudios.com/">WebDevStudios</a></p>
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
