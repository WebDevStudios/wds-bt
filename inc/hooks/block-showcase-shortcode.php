<?php
/**
 * Block Showcase shortcode using WP_Block_Processor.
 *
 * Renders all registered blocks (core and custom) in a showcase format.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Register block showcase shortcode.
 *
 * @return void
 */
function register_block_showcase_shortcode() {
	add_shortcode( 'wdsbt_block_showcase', __NAMESPACE__ . '\render_block_showcase_shortcode' );
}
add_action( 'init', __NAMESPACE__ . '\register_block_showcase_shortcode' );

/**
 * Render block showcase shortcode.
 *
 * @param array  $atts    Shortcode attributes. Unused but required for shortcode signature.
 * @param string $content Shortcode content. Unused but required for shortcode signature.
 * @return string Rendered HTML.
 */
function render_block_showcase_shortcode( $atts = array(), $content = '' ) {
	// Unused parameters - required for shortcode callback signature.
	unset( $atts, $content );
	// Only allow admins to see the showcase.
	if ( ! current_user_can( 'manage_options' ) ) {
		return '';
	}

	$organized_blocks = get_all_registered_blocks();
	$category_labels  = array(
		'text'    => 'Text Blocks',
		'media'   => 'Media Blocks',
		'design'  => 'Design Blocks',
		'widgets' => 'Widget Blocks',
		'theme'   => 'Theme Blocks',
		'embeds'  => 'Embed Blocks',
		'wdsbt'   => 'WDS BT Custom Blocks',
		'other'   => 'Other Blocks',
	);

	ob_start();
	?>

	<div class="wdsbt-block-showcase">
		<?php
		// Process core blocks by category.
		$core_blocks_by_category = array();
		foreach ( $organized_blocks['core'] as $block_name => $block_type ) {
			$category = get_block_category( $block_name );
			if ( ! isset( $core_blocks_by_category[ $category ] ) ) {
				$core_blocks_by_category[ $category ] = array();
			}
			$core_blocks_by_category[ $category ][ $block_name ] = $block_type;
		}

		// Display core blocks by category.
		foreach ( $core_blocks_by_category as $category => $blocks ) :
			if ( empty( $blocks ) ) {
				continue;
			}
			?>

			<div class="wdsbt-showcase-category">
				<h2 class="wdsbt-showcase-category-title"><?php echo esc_html( $category_labels[ $category ] ?? ucfirst( $category ) ); ?></h2>

				<div class="wdsbt-showcase-blocks">
					<?php foreach ( $blocks as $block_name => $block_type ) : ?>
						<?php
						$block_html = render_block_for_showcase( $block_name, $block_type );
						if ( empty( $block_html ) ) {
							continue;
						}
						?>

						<div class="wdsbt-showcase-block-card">
							<h4 class="wdsbt-showcase-block-title"><?php echo esc_html( get_block_display_name( $block_name ) ); ?></h4>
							<div class="wdsbt-showcase-block-meta">
								<code class="wdsbt-showcase-block-name"><?php echo esc_html( $block_name ); ?></code>
							</div>
							<div class="wdsbt-showcase-block-preview">
								<?php echo wp_kses_post( $block_html ); ?>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>

		<?php endforeach; ?>

		<?php if ( ! empty( $organized_blocks['wdsbt'] ) ) : ?>
			<div class="wdsbt-showcase-category">
				<h2 class="wdsbt-showcase-category-title"><?php echo esc_html( $category_labels['wdsbt'] ); ?></h2>

				<div class="wdsbt-showcase-blocks">
					<?php foreach ( $organized_blocks['wdsbt'] as $block_name => $block_type ) : ?>
						<?php
						$block_html = render_block_for_showcase( $block_name, $block_type );
						if ( empty( $block_html ) ) {
							continue;
						}
						?>

						<div class="wdsbt-showcase-block-card">
							<h4 class="wdsbt-showcase-block-title"><?php echo esc_html( get_block_display_name( $block_name ) ); ?></h4>
							<div class="wdsbt-showcase-block-meta">
								<code class="wdsbt-showcase-block-name"><?php echo esc_html( $block_name ); ?></code>
							</div>
							<div class="wdsbt-showcase-block-preview">
								<?php echo wp_kses_post( $block_html ); ?>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $organized_blocks['other'] ) ) : ?>
			<div class="wdsbt-showcase-category">
				<h2 class="wdsbt-showcase-category-title"><?php echo esc_html( $category_labels['other'] ); ?></h2>

				<div class="wdsbt-showcase-blocks">
					<?php foreach ( $organized_blocks['other'] as $block_name => $block_type ) : ?>
						<?php
						$block_html = render_block_for_showcase( $block_name, $block_type );
						if ( empty( $block_html ) ) {
							continue;
						}
						?>

						<div class="wdsbt-showcase-block-card">
							<h4 class="wdsbt-showcase-block-title"><?php echo esc_html( get_block_display_name( $block_name ) ); ?></h4>
							<div class="wdsbt-showcase-block-meta">
								<code class="wdsbt-showcase-block-name"><?php echo esc_html( $block_name ); ?></code>
							</div>
							<div class="wdsbt-showcase-block-preview">
								<?php echo wp_kses_post( $block_html ); ?>
							</div>
						</div>

					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}

