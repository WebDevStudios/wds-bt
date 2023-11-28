<?php
/**
 * Title: Split footer with logo and links
 * Slug: powder/footer-split
 * Categories: footer
 * Block Types: core/template-part/footer
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0"},"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","right":"30px","left":"30px"},"blockGap":"0px"}},"layout":{"type":"constrained"},"fontSize":"tiny"} -->
<div class="wp-block-group alignfull has-tiny-font-size" style="margin-top:0;padding-top:var(--wp--preset--spacing--medium);padding-right:30px;padding-bottom:var(--wp--preset--spacing--medium);padding-left:30px">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"0","left":"0"},"margin":{"bottom":"var:preset|spacing|x-large"}}}} -->
	<div class="wp-block-columns alignwide" style="margin-bottom:var(--wp--preset--spacing--x-large)">
		<!-- wp:column {"width":"60%"} -->
		<div class="wp-block-column" style="flex-basis:60%">
			<!-- wp:group {"style":{"spacing":{"blockGap":"10px","margin":{"bottom":"var:preset|spacing|small"}},"layout":{"selfStretch":"fixed","flexSize":"200px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--small)">
				<!-- wp:site-logo {"width":30,"shouldSyncIcon":false} /-->
				<!-- wp:site-title {"style":{"typography":{"textTransform":"uppercase","lineHeight":"1"}},"fontSize":"medium"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:columns -->
			<div class="wp-block-columns">
				<!-- wp:column {"style":{"spacing":{"blockGap":"15px"}}} -->
				<div class="wp-block-column">
					<!-- wp:heading {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase"}},"fontSize":"tiny"} -->
					<h2 class="wp-block-heading has-tiny-font-size" style="line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Heading', 'powder' ); ?></h2>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"2"}},"className":"is-style-no-style"} -->
					<ul class="is-style-no-style" style="line-height:2">
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #1', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #2', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #3', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #4', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
					</ul>
					<!-- /wp:list -->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"style":{"spacing":{"blockGap":"15px"}}} -->
				<div class="wp-block-column">
					<!-- wp:heading {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase"}},"fontSize":"tiny"} -->
					<h2 class="wp-block-heading has-tiny-font-size" style="line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Heading', 'powder' ); ?></h2>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"2"}},"className":"is-style-no-style"} -->
					<ul class="is-style-no-style" style="line-height:2">
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #1', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #2', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #3', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #4', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
					</ul>
					<!-- /wp:list -->
				</div>
				<!-- /wp:column -->
				<!-- wp:column {"style":{"spacing":{"blockGap":"15px"}}} -->
				<div class="wp-block-column">
					<!-- wp:heading {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase"}},"fontSize":"tiny"} -->
					<h2 class="wp-block-heading has-tiny-font-size" style="line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Heading', 'powder' ); ?></h2>
					<!-- /wp:heading -->
					<!-- wp:list {"style":{"typography":{"lineHeight":"2"}},"className":"is-style-no-style"} -->
					<ul class="is-style-no-style" style="line-height:2">
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #1', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #2', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #3', 'powder' ); ?></a></li>
						<!-- /wp:list-item -->
						<!-- wp:list-item -->
						<li><a href="#"><?php echo esc_html__( 'Link #4', 'powder' ); ?></a></li>
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
	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|small"}},"border":{"top":{"color":"#e5e5e5","width":"1px"}}},"layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide" style="border-top-color:#e5e5e5;border-top-width:1px;padding-top:var(--wp--preset--spacing--small)">
		<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
		<div class="wp-block-group">
			<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:paragraph -->
				<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:site-title {"level":0,"isLink":false,"style":{"typography":{"fontSize":"15px"}}} /-->
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
		<!-- wp:social-links {"iconColor":"contrast","iconColorValue":"#000000","openInNewTab":true,"size":"has-small-icon-size","className":"is-style-outline"} -->
		<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-outline">
			<!-- wp:social-link {"url":"https://twitter.com/","service":"twitter"} /-->
			<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->
			<!-- wp:social-link {"url":"https://www.linkedin.com/","service":"linkedin"} /-->
			<!-- wp:social-link {"url":"https://www.facebook.com/","service":"facebook"} /-->
		</ul>
		<!-- /wp:social-links -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
