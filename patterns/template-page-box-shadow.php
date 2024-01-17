<?php
/**
 * Title: Page content with box shadow
 * Slug: powder/template-page-box-shadow
 * Categories: powder-template
 * Template Types: page
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"tagName":"main","align":"full","style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"default"}} -->
<main class="wp-block-group alignfull" style="margin-top:0">
	<!-- wp:group {"tagName":"header","style":{"spacing":{"padding":{"top":"var:preset|spacing|large","bottom":"var:preset|spacing|x-large"}}},"backgroundColor":"contrast","textColor":"base","className":"entry-header","layout":{"type":"constrained","wideSize":"800px"}} -->
	<header class="wp-block-group entry-header has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--large);padding-bottom:var(--wp--preset--spacing--x-large)">
		<!-- wp:post-title {"textAlign":"center","level":1,"fontSize":"max-48"} /-->
	</header>
	<!-- /wp:group -->
	<!-- wp:group {"align":"full","style":{"dimensions":{"minHeight":"100px"},"spacing":{"margin":{"top":"0"}}},"backgroundColor":"contrast","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-contrast-background-color has-background" style="min-height:100px;margin-top:0"></div>
	<!-- /wp:group -->
	<!-- wp:group {"tagName":"main","style":{"spacing":{"margin":{"top":"0"},"padding":{"left":"30px","right":"30px"}}},"layout":{"type":"constrained","wideSize":"960px"}} -->
	<main class="wp-block-group" style="margin-top:0;padding-right:30px;padding-left:30px">
		<!-- wp:group {"tagName":"article","layout":{"type":"default"}} -->
		<article class="wp-block-group">
			<!-- wp:group {"backgroundColor":"base","className":"is-style-position-relative is-style-positive-zindex is-style-pull-100 is-style-shadow-light","layout":{"type":"default"}} -->
			<div class="wp-block-group is-style-position-relative is-style-positive-zindex is-style-pull-100 is-style-shadow-light has-base-background-color has-background">
				<!-- wp:post-featured-image {"aspectRatio":"16/9"} /-->
				<!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","right":"30px","left":"30px"}}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group" style="margin-top:0px;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
					<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</article>
		<!-- /wp:group -->
	</main>
	<!-- /wp:group -->
	<!-- wp:group {"align":"full","style":{"dimensions":{"minHeight":"100px"}},"backgroundColor":"contrast","className":"is-style-pull-100","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-pull-100 has-contrast-background-color has-background" style="min-height:100px"></div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
