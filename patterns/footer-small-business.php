<?php
/**
 * Title: Small business footer with columns of text.
 * Slug: powder/footer-small-business
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0px;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained","wideSize":"600px","justifyContent":"left"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:heading {"fontSize":"max-48"} -->
		<h2 class="wp-block-heading has-max-48-font-size"><?php echo esc_html__( 'Designed with love. Powered by WordPress.', 'powder' ); ?></h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->
	<!-- wp:columns {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|large"}}}} -->
	<div class="wp-block-columns alignwide" style="margin-top:var(--wp--preset--spacing--large)">
		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase"},"color":{"text":"#525252"}},"fontSize":"tiny"} -->
			<h3 class="wp-block-heading has-text-color has-tiny-font-size" style="color:#525252;text-transform:uppercase"><?php echo esc_html__( 'Main Office', 'powder' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:list {"style":{"typography":{"lineHeight":"1.5"}},"className":"is-style-no-style","fontSize":"small"} -->
			<ul style="line-height:1.5" class="is-style-no-style has-small-font-size">
				<!-- wp:list-item -->
				<li>1989 Evermore Lane</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Wonderland, CA 90909</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><a href="#">847.555.8080</a></li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"60%"} -->
		<div class="wp-block-column" style="flex-basis:60%">
			<!-- wp:columns -->
			<div class="wp-block-columns">
				<!-- wp:column {"width":""} -->
				<div class="wp-block-column">
					<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase"},"color":{"text":"#525252"}},"fontSize":"tiny"} -->
					<h3 class="wp-block-heading has-text-color has-tiny-font-size" style="color:#525252;text-transform:uppercase">Chicago</h3>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"1.5"}},"className":"is-style-no-style","fontSize":"small"} -->
					<ul style="line-height:1.5" class="is-style-no-style has-small-font-size">
						<!-- wp:list-item -->
						<li>Brooke Swift</li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><?php echo esc_html__( 'Creative Director', 'powder' ); ?></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="mailto:brooke@example.com">brooke@example.com</a></li>
						<!-- /wp:list-item -->
					</ul>
					<!-- /wp:list -->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"width":""} -->
				<div class="wp-block-column">
					<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase"},"color":{"text":"#525252"}},"fontSize":"tiny"} -->
					<h3 class="wp-block-heading has-text-color has-tiny-font-size" style="color:#525252;text-transform:uppercase">Nashville</h3>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"1.5"}},"className":"is-style-no-style","fontSize":"small"} -->
					<ul style="line-height:1.5" class="is-style-no-style has-small-font-size">
						<!-- wp:list-item -->
						<li>Sarah Taylor</li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><?php echo esc_html__( 'Creative Director', 'powder' ); ?></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="mailto:sarah@example.com">sarah@example.com</a></li>
						<!-- /wp:list-item -->
					</ul>
					<!-- /wp:list -->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"width":""} -->
				<div class="wp-block-column">
					<!-- wp:heading {"level":3,"style":{"typography":{"textTransform":"uppercase"},"color":{"text":"#525252"}},"fontSize":"tiny"} -->
					<h3 class="wp-block-heading has-text-color has-tiny-font-size" style="color:#525252;text-transform:uppercase">New York</h3>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"1.5"}},"className":"is-style-no-style","fontSize":"small"} -->
					<ul style="line-height:1.5" class="is-style-no-style has-small-font-size">
						<!-- wp:list-item -->
						<li>Hayley Jones</li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><?php echo esc_html__( 'Creative Director', 'powder' ); ?></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="mailto:hayley@example.com">hayley@example.com</a></li>
						<!-- /wp:list-item -->
					</ul>
					<!-- /wp:list -->
				</div>
				<!-- /wp:column -->
			</div>
			<!-- /wp:columns -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|medium"}}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"},"fontSize":"x-small"} -->
	<div class="wp-block-group alignwide has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--medium)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|x-small"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph -->
			<p><a href="https://twitter.com/">Twitter</a></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p><a href="https://instagram.com/">Instagram</a></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p><a href="https://www.linkedin.com/">LinkedIn</a></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p><a href="https://www.facebook.com/">Facebook</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
		<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontStyle":"normal","fontWeight":"300"}},"fontSize":"x-small"} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:paragraph -->
			<p> · </p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph -->
			<p><a href="https://powderstudio.com/">Theme</a> by <a href="https://briangardner.com/">Brian Gardner</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
