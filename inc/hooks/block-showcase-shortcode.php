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
 * Allow data URIs in kses for block showcase images.
 *
 * @param array $protocols Allowed protocols.
 * @return array Modified protocols.
 */
function allow_data_uris_in_showcase( $protocols ) {
	$protocols[] = 'data';
	return $protocols;
}

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

		// Define the order for core block categories.
		$core_category_order = array( 'text', 'media', 'design', 'widgets', 'theme', 'embeds' );

		// Display core blocks by category in the specified order.
		foreach ( $core_category_order as $category ) :
			if ( ! isset( $core_blocks_by_category[ $category ] ) || empty( $core_blocks_by_category[ $category ] ) ) {
				continue;
			}
			$blocks = $core_blocks_by_category[ $category ];
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
							<?php
							$block_attributes = get_block_attributes_info( $block_type );
							if ( ! empty( $block_attributes ) ) :
								?>
								<div class="wdsbt-showcase-block-attributes">
									<details class="wdsbt-attributes-details">
										<summary class="wdsbt-attributes-summary">Attributes (<?php echo esc_html( count( $block_attributes ) ); ?>)</summary>
										<div class="wdsbt-attributes-list">
											<?php foreach ( $block_attributes as $attr_name => $attr_info ) : ?>
												<div class="wdsbt-attribute-item">
													<code class="wdsbt-attribute-name"><?php echo esc_html( $attr_name ); ?></code>
													<?php
													$type_display = $attr_info['type'];
													if ( is_array( $type_display ) || is_object( $type_display ) ) {
														$type_display = wp_json_encode( $type_display );
													} else {
														$type_display = (string) $type_display;
													}
													?>
													<span class="wdsbt-attribute-type"><?php echo esc_html( $type_display ); ?></span>
													<?php if ( null !== $attr_info['default'] ) : ?>
														<?php
														$default_display = $attr_info['default'];
														if ( is_array( $default_display ) || is_object( $default_display ) ) {
															$default_display = wp_json_encode( $default_display );
														} else {
															$default_display = (string) $default_display;
														}
														?>
														<span class="wdsbt-attribute-default">default: <?php echo esc_html( $default_display ); ?></span>
													<?php endif; ?>
													<?php if ( isset( $attr_info['enum'] ) && is_array( $attr_info['enum'] ) ) : ?>
														<?php
														$enum_display = array_map(
															function ( $value ) {
																if ( is_array( $value ) || is_object( $value ) ) {
																	return wp_json_encode( $value );
																}
																return (string) $value;
															},
															$attr_info['enum']
														);
														?>
														<span class="wdsbt-attribute-enum">options: <?php echo esc_html( implode( ', ', $enum_display ) ); ?></span>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</details>
								</div>
							<?php endif; ?>
							<div class="wdsbt-showcase-block-preview">
								<?php
								// Temporarily allow data URIs for showcase images.
								add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								echo wp_kses_post( $block_html );
								remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								?>
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
							<?php
							$block_attributes = get_block_attributes_info( $block_type );
							if ( ! empty( $block_attributes ) ) :
								?>
								<div class="wdsbt-showcase-block-attributes">
									<details class="wdsbt-attributes-details">
										<summary class="wdsbt-attributes-summary">Attributes (<?php echo esc_html( count( $block_attributes ) ); ?>)</summary>
										<div class="wdsbt-attributes-list">
											<?php foreach ( $block_attributes as $attr_name => $attr_info ) : ?>
												<div class="wdsbt-attribute-item">
													<code class="wdsbt-attribute-name"><?php echo esc_html( $attr_name ); ?></code>
													<?php
													$type_display = $attr_info['type'];
													if ( is_array( $type_display ) || is_object( $type_display ) ) {
														$type_display = wp_json_encode( $type_display );
													} else {
														$type_display = (string) $type_display;
													}
													?>
													<span class="wdsbt-attribute-type"><?php echo esc_html( $type_display ); ?></span>
													<?php if ( null !== $attr_info['default'] ) : ?>
														<?php
														$default_display = $attr_info['default'];
														if ( is_array( $default_display ) || is_object( $default_display ) ) {
															$default_display = wp_json_encode( $default_display );
														} else {
															$default_display = (string) $default_display;
														}
														?>
														<span class="wdsbt-attribute-default">default: <?php echo esc_html( $default_display ); ?></span>
													<?php endif; ?>
													<?php if ( isset( $attr_info['enum'] ) && is_array( $attr_info['enum'] ) ) : ?>
														<?php
														$enum_display = array_map(
															function ( $value ) {
																if ( is_array( $value ) || is_object( $value ) ) {
																	return wp_json_encode( $value );
																}
																return (string) $value;
															},
															$attr_info['enum']
														);
														?>
														<span class="wdsbt-attribute-enum">options: <?php echo esc_html( implode( ', ', $enum_display ) ); ?></span>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</details>
								</div>
							<?php endif; ?>
							<div class="wdsbt-showcase-block-preview">
								<?php
								// Temporarily allow data URIs for showcase images.
								add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								echo wp_kses_post( $block_html );
								remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								?>
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
							<?php
							$block_attributes = get_block_attributes_info( $block_type );
							if ( ! empty( $block_attributes ) ) :
								?>
								<div class="wdsbt-showcase-block-attributes">
									<details class="wdsbt-attributes-details">
										<summary class="wdsbt-attributes-summary">Attributes (<?php echo esc_html( count( $block_attributes ) ); ?>)</summary>
										<div class="wdsbt-attributes-list">
											<?php foreach ( $block_attributes as $attr_name => $attr_info ) : ?>
												<div class="wdsbt-attribute-item">
													<code class="wdsbt-attribute-name"><?php echo esc_html( $attr_name ); ?></code>
													<?php
													$type_display = $attr_info['type'];
													if ( is_array( $type_display ) || is_object( $type_display ) ) {
														$type_display = wp_json_encode( $type_display );
													} else {
														$type_display = (string) $type_display;
													}
													?>
													<span class="wdsbt-attribute-type"><?php echo esc_html( $type_display ); ?></span>
													<?php if ( null !== $attr_info['default'] ) : ?>
														<?php
														$default_display = $attr_info['default'];
														if ( is_array( $default_display ) || is_object( $default_display ) ) {
															$default_display = wp_json_encode( $default_display );
														} else {
															$default_display = (string) $default_display;
														}
														?>
														<span class="wdsbt-attribute-default">default: <?php echo esc_html( $default_display ); ?></span>
													<?php endif; ?>
													<?php if ( isset( $attr_info['enum'] ) && is_array( $attr_info['enum'] ) ) : ?>
														<?php
														$enum_display = array_map(
															function ( $value ) {
																if ( is_array( $value ) || is_object( $value ) ) {
																	return wp_json_encode( $value );
																}
																return (string) $value;
															},
															$attr_info['enum']
														);
														?>
														<span class="wdsbt-attribute-enum">options: <?php echo esc_html( implode( ', ', $enum_display ) ); ?></span>
													<?php endif; ?>
												</div>
											<?php endforeach; ?>
										</div>
									</details>
								</div>
							<?php endif; ?>
							<div class="wdsbt-showcase-block-preview">
								<?php
								// Temporarily allow data URIs for showcase images.
								add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								echo wp_kses_post( $block_html );
								remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								?>
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

