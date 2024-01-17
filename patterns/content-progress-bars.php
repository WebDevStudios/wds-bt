<?php
/**
 * Title: Section that shows progress bars
 * Slug: powder/content-progress-bars
 * Categories: powder-content
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}}},"layout":{"type":"constrained"},"metadata":{"name":"Progress Bars"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-bottom is-not-stacked-on-mobile">
		<!-- wp:column {"verticalAlignment":"bottom","width":"100%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:100%">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( 'Item #1', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( '100%', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-contrast-background-color has-background">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"bottom","width":"0%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:0%">
			<!-- wp:group {"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-bottom is-not-stacked-on-mobile">
		<!-- wp:column {"verticalAlignment":"bottom","width":"90%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:90%">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( 'Item #2', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( '90%', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-contrast-background-color has-background">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"bottom","width":"10%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:10%">
			<!-- wp:group {"style":{"color":{"background":"#e5e5e5"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-background" style="background-color:#e5e5e5">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-bottom is-not-stacked-on-mobile">
		<!-- wp:column {"verticalAlignment":"bottom","width":"80%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:80%">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( 'Item #3', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( '80%', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-contrast-background-color has-background">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"bottom","width":"20%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:20%">
			<!-- wp:group {"style":{"color":{"background":"#e5e5e5"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-background" style="background-color:#e5e5e5">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-bottom is-not-stacked-on-mobile">
		<!-- wp:column {"verticalAlignment":"bottom","width":"70%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:70%">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( 'Item 41', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( '70%', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-contrast-background-color has-background">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"bottom","width":"30%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:30%">
			<!-- wp:group {"style":{"color":{"background":"#e5e5e5"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-background" style="background-color:#e5e5e5">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"style":{"spacing":{"blockGap":{"top":"0","left":"0"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-bottom is-not-stacked-on-mobile">
		<!-- wp:column {"verticalAlignment":"bottom","width":"60%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:60%">
			<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"},"fontSize":"small"} -->
			<div class="wp-block-group has-small-font-size">
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( 'Item #5', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:paragraph -->
				<p><?php echo esc_html__( '60%', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-contrast-background-color has-background">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"bottom","width":"40%","style":{"spacing":{"blockGap":"0"}}} -->
		<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:40%">
			<!-- wp:group {"style":{"color":{"background":"#e5e5e5"}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-background" style="background-color:#e5e5e5">
				<!-- wp:spacer {"height":"20px"} -->
				<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
				<!-- /wp:spacer -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
