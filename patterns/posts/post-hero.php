<?php
/**
 * Title: Single Post Hero
 * Slug: wdsbt/post-hero
 * Categories: hero
 * Template Types: single-post
 * Block Types: custom/post-hero
 *
 * @package wdsbt
 */

?>

<!-- wp:group {"metadata":{"name":"Hero"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"backgroundColor":"primary-50","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-primary-50-background-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">

	<!-- wp:columns {"verticalAlignment":null,"align":"wide"} -->
	<div class="wp-block-columns alignwide">

		<!-- wp:column {"verticalAlignment":"top","layout":{"type":"constrained"}} -->
		<div class="wp-block-column is-vertically-aligned-top">
			<!-- wp:post-terms {"term":"category","prefix":""} /-->
			<!-- wp:post-title /-->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"verticalAlignment":"center","layout":{"type":"constrained"}} -->
		<div class="wp-block-column is-vertically-aligned-center">
			<!-- wp:post-featured-image {"aspectRatio":"16/9","style":{"border":{"radius":"24px"}}} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
