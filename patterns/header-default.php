<?php
/**
 * Title: Default header
 * Slug: wdsbt/header
 * Categories: header
 * Block Types: core/template-part/header
 *
 * @package wdsbt
 */

// Determine whether to display site logo or site title.
$wds_site_info = has_custom_logo() ? '<!-- wp:site-logo {"width":150,"shouldSyncIcon":true,"style":{"layout":{"selfStretch":"fit","flexSize":null}}} /-->' : '<!-- wp:site-title /-->';
?>

<!-- wp:group {"tagName":"header","metadata":{"name":"Header"},"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"30px","bottom":"30px"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" id="top" style="margin-top:0px;padding-top:30px;padding-bottom:30px">

	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">

		<?php echo wp_kses_post( $wds_site_info ); ?>

		<!-- wp:group {"templateLock":false,"lock":{"move":false,"remove":false},"metadata":{"name":"Primary Menu"},"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
		<div class="wp-block-group">

			<!-- wp:navigation {"icon":"menu","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right","flexWrap":"nowrap","orientation":"horizontal"}} /-->

		</div>
		<!-- /wp:group -->

	</div>
	<!-- /wp:group -->

</div>
<!-- /wp:group -->
