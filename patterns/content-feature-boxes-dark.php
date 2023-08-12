<?php
/**
 * Title: Feature boxes with image and text.
 * Slug: powder/content-feature-boxes-dark
 * Description: Section with columns of feature boxes.
 * Categories: content
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|x-large","bottom":"var:preset|spacing|x-large","left":"30px","right":"30px"},"margin":{"top":"0"}}},"backgroundColor":"contrast","textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-contrast-background-color has-text-color has-background" style="margin-top:0;padding-top:var(--wp--preset--spacing--x-large);padding-right:30px;padding-bottom:var(--wp--preset--spacing--x-large);padding-left:30px">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|small"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"style":{"border":{"color":"#262626","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|small","right":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|x-small"}}} -->
		<div class="wp-block-column has-border-color" style="border-color:#262626;border-width:1px;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--small)">
			<!-- wp:image {"align":"center","width":30,"height":30,"scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:30px;height:30px" width="30" height="30"/></figure>
			<!-- /wp:image -->
			<!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"x-small"} -->
			<h2 class="wp-block-heading has-text-align-center has-x-small-font-size" style="text-transform:uppercase"><?php echo esc_html__( 'Feature Headline', 'powder' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size"><?php echo esc_html__( 'Design beautiful WordPress websites with the stylish Powder theme.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"style":{"border":{"color":"#262626","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|small","right":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|x-small"}}} -->
		<div class="wp-block-column has-border-color" style="border-color:#262626;border-width:1px;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--small)">
			<!-- wp:image {"align":"center","width":30,"height":30,"scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:30px;height:30px" width="30" height="30"/></figure>
			<!-- /wp:image -->
			<!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"x-small"} -->
			<h2 class="wp-block-heading has-text-align-center has-x-small-font-size" style="text-transform:uppercase"><?php echo esc_html__( 'Feature Headline', 'powder' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size"><?php echo esc_html__( 'Design beautiful WordPress websites with the stylish Powder theme.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"style":{"border":{"color":"#262626","width":"1px"},"spacing":{"padding":{"top":"var:preset|spacing|medium","bottom":"var:preset|spacing|medium","left":"var:preset|spacing|small","right":"var:preset|spacing|small"},"blockGap":"var:preset|spacing|x-small"}}} -->
		<div class="wp-block-column has-border-color" style="border-color:#262626;border-width:1px;padding-top:var(--wp--preset--spacing--medium);padding-right:var(--wp--preset--spacing--small);padding-bottom:var(--wp--preset--spacing--medium);padding-left:var(--wp--preset--spacing--small)">
			<!-- wp:image {"align":"center","width":30,"height":30,"scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
			<figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ) . '/assets/images/icon-circle-check-light.svg'; ?>" alt="Circle check light icon" style="object-fit:cover;width:30px;height:30px" width="30" height="30"/></figure>
			<!-- /wp:image -->
			<!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"x-small"} -->
			<h2 class="wp-block-heading has-text-align-center has-x-small-font-size" style="text-transform:uppercase"><?php echo esc_html__( 'Feature Headline', 'powder' ); ?></h2>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
			<p class="has-text-align-center has-small-font-size"><?php echo esc_html__( 'Design beautiful WordPress websites with the stylish Powder theme.', 'powder' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
