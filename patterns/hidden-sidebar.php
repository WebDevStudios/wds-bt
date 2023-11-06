<?php
/**
 * Title: Sidebar on pages and posts
 * Slug: powder/sidebar
 * Inserter: no
 */
?>
<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|small"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"constrained"},"metadata":{"name":"<?php echo esc_html__( 'About', 'powder' ); ?>"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'About', 'powder' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:paragraph -->
		<p><?php echo esc_html__( 'With Powder, we want to revolutionize how websites are made by embracing the power of WordPress and blocks.', 'powder' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"constrained"},"metadata":{"name":"<?php echo esc_html__( 'Connect', 'powder' ); ?>"}} -->
	<div class="wp-block-group">	
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Connect', 'powder' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:social-links {"iconBackgroundColor":"contrast","iconBackgroundColorValue":"#000000","className":"is-style-default"} -->
		<ul class="wp-block-social-links has-icon-background-color is-style-default">
			<!-- wp:social-link {"url":"https://twitter.com/","service":"twitter"} /-->
			<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
			<!-- wp:social-link {"url":"https://www.linkedin.com/","service":"linkedin"} /-->
			<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
			<!-- wp:social-link {"url":"https://www.tiktok.com/","service":"tiktok"} /-->
		</ul>
		<!-- /wp:social-links -->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"constrained"},"metadata":{"name":"<?php echo esc_html__( 'Recent', 'powder' ); ?>"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Recent', 'powder' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:latest-posts /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"constrained"},"metadata":{"name":"<?php echo esc_html__( 'Search', 'powder' ); ?>"}} -->
	<div class="wp-block-group">
		<!-- wp:heading -->
		<h2 class="wp-block-heading"><?php echo esc_html__( 'Search', 'powder' ); ?></h2>
		<!-- /wp:heading -->
		<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Enter keywords...","buttonText":"Search"} /-->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
