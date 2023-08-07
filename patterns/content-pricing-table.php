<?php
/**
 * Title: Pricing table with three columns.
 * Slug: powder/content-pricing-table
 * Description: Pricing table with three columns.
 * Categories: content
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"right":"30px","left":"30px","top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large"},"margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|small","left":"var:preset|spacing|small"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":""} -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","right":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}},"fontSize":"tiny"} -->
					<p class="has-tiny-font-size" style="font-style:normal;font-weight:400;line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Basic', 'powder' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1"}},"fontSize":"max-48"} -->
						<p class="has-text-align-center has-max-48-font-size" style="font-style:normal;font-weight:400;line-height:1">$95</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"x-small"} -->
						<p class="has-x-small-font-size">/ <?php echo esc_html__( 'year', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:paragraph {"style":{"spacing":{"margin":{"top":"var:preset|spacing|x-small"}},"typography":{"lineHeight":"1.5"}},"fontSize":"x-small"} -->
				<p class="has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--x-small);line-height:1.5"><?php echo esc_html__( 'Perfect for WordPress designers who are curious or just getting started.', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Powder theme', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Basic support', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-x-light.svg'; ?>" alt="Circle x light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Figma design library', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-x-light.svg'; ?>" alt="Circle x light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Access to private Slack', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|small"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--small)">
					<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Get Basic →', 'powder' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":""} -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","right":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}},"fontSize":"tiny"} -->
					<p class="has-tiny-font-size" style="font-style:normal;font-weight:400;line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Professional', 'powder' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1"}},"fontSize":"max-48"} -->
						<p class="has-text-align-center has-max-48-font-size" style="font-style:normal;font-weight:400;line-height:1">$195</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"x-small"} -->
						<p class="has-x-small-font-size">/ <?php echo esc_html__( 'year', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}},"fontSize":"x-small"} -->
				<p class="has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--x-small);line-height:1.5"><?php echo esc_html__( 'Perfect for WordPress designers who see the value of this amazing offer.', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-full is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light'; ?>.svg" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Powder theme', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-full is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light'; ?>.svg" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Premium support', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-full is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light'; ?>.svg" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Figma design library', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-full is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light'; ?>.svg" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Access to private Slack', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|small"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--small)">
					<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Get Professional →', 'powder' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":""} -->
		<div class="wp-block-column">
			<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|medium","right":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|medium"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-base-color has-contrast-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--medium);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--medium)">
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}},"fontSize":"tiny"} -->
					<p class="has-tiny-font-size" style="font-style:normal;font-weight:400;line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Lifetime', 'powder' ); ?></p>
					<!-- /wp:paragraph -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left","verticalAlignment":"bottom"}} -->
					<div class="wp-block-group">
						<!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"400","lineHeight":"1"}},"fontSize":"max-48"} -->
						<p class="has-text-align-center has-max-48-font-size" style="font-style:normal;font-weight:400;line-height:1">$295</p>
						<!-- /wp:paragraph -->
						<!-- wp:paragraph {"fontSize":"x-small"} -->
						<p class="has-x-small-font-size">/ <?php echo esc_html__( 'once', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"},"spacing":{"margin":{"top":"var:preset|spacing|x-small"}}},"fontSize":"x-small"} -->
				<p class="has-x-small-font-size" style="margin-top:var(--wp--preset--spacing--x-small);line-height:1.5"><?php echo esc_html__( 'Perfect for WordPress designers who want the deal of a lifetime. Literally.', 'powder' ); ?></p>
				<!-- /wp:paragraph -->
				<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Powder theme', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Premium support', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Figma design library', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
					<div class="wp-block-group">
						<!-- wp:image {"width":20,"height":20,"scale":"cover","sizeSlug":"large","linkDestination":"none","className":"is-style-display-block"} -->
						<figure class="wp-block-image size-large is-resized is-style-display-block"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:20px;height:20px" width="20" height="20"/></figure>
						<!-- /wp:image -->
						<!-- wp:paragraph {"fontSize":"small"} -->
						<p class="has-small-font-size"><?php echo esc_html__( 'Access to private Slack', 'powder' ); ?></p>
						<!-- /wp:paragraph -->
					</div>
					<!-- /wp:group -->
				</div>
				<!-- /wp:group -->
				<!-- wp:buttons {"style":{"spacing":{"margin":{"top":"var:preset|spacing|small"}}}} -->
				<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--small)">
					<!-- wp:button {"backgroundColor":"base","textColor":"contrast","width":100} -->
					<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link has-contrast-color has-base-background-color has-text-color has-background wp-element-button" href="#"><?php echo esc_html__( 'Get Lifetime →', 'powder' ); ?></a></div>
					<!-- /wp:button -->
				</div>
				<!-- /wp:buttons -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
