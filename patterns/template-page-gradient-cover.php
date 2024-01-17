<?php
/**
 * Title: Page content with gradient cover
 * Slug: powder/template-page-gradient-cover
 * Categories: powder-template
 * Template Types: page
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:cover {"useFeaturedImage":true,"dimRatio":50,"minHeight":480,"isDark":false,"style":{"spacing":{"margin":{"top":"0"}}},"className":"is-style-gradient","layout":{"type":"default"}} -->
<div class="wp-block-cover is-light is-style-gradient" style="margin-top:0;min-height:480px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container">
<!-- wp:group {"tagName":"header","style":{"spacing":{"blockGap":"10px"}},"textColor":"base","layout":{"type":"constrained","wideSize":"800px"}} -->
<header class="wp-block-group has-base-color has-text-color">
		<!-- wp:post-title {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"300","letterSpacing":"-1px"}},"fontSize":"max-60"} /-->
	</div>
	<!-- /wp:group -->
</div></div>
<!-- /wp:cover -->
<!-- wp:group {"tagName":"main","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|large"}}}} -->
<main class="wp-block-group" style="margin-top:0;padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--large)">
	<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
