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
		$core_blocks_by_category = array();
		foreach ( $organized_blocks['core'] as $block_name => $block_type ) {
			$category = get_block_category( $block_name );
			if ( ! isset( $core_blocks_by_category[ $category ] ) ) {
				$core_blocks_by_category[ $category ] = array();
			}
			$core_blocks_by_category[ $category ][ $block_name ] = $block_type;
		}

		$core_category_order = array( 'text', 'media', 'design', 'widgets', 'theme', 'embeds' );

		if ( ! empty( $organized_blocks['core'] ) ) :
			?>
			<h2 class="wdsbt-showcase-section-heading">Core Blocks</h2>
			<?php
		endif;

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
				<div role="group" class="wp-block-accordion">
					<div class="wp-block-accordion-item">
						<div class="wp-block-accordion-heading wdsbt-showcase-category-title">
							<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
								<span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $category_labels[ $category ] ?? ucfirst( $category ) ); ?> (<?php echo esc_html( count( $blocks ) ); ?>)</span>
								<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
							</button>
						</div>
						<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
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
								add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								$allowed_html           = wp_kses_allowed_html( 'post' );
								$allowed_html['figure'] = array(
									'class' => true,
								);
								$allowed_html['img']    = array(
									'src'    => true,
									'alt'    => true,
									'width'  => true,
									'height' => true,
								);
								$allowed_html['div']    = array(
									'class' => true,
								);
								$allowed_html['input']  = array(
									'type'             => true,
									'name'             => true,
									'value'            => true,
									'placeholder'      => true,
									'required'         => true,
									'id'               => true,
									'class'            => true,
									'aria-label'       => true,
									'aria-labelledby'  => true,
									'aria-describedby' => true,
								);
								$allowed_html['button'] = array(
									'type'            => true,
									'class'           => true,
									'aria-label'      => true,
									'aria-labelledby' => true,
								);
								$allowed_html['form']   = array(
									'action' => true,
									'method' => true,
									'class'  => true,
									'role'   => true,
								);
								$allowed_html['label']  = array(
									'for'   => true,
									'class' => true,
								);
								echo wp_kses( $block_html, $allowed_html );
								remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								?>
							</div>
						</div>

					<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

		<?php if ( ! empty( $organized_blocks['wdsbt'] ) ) : ?>
			<h2 class="wdsbt-showcase-section-heading">WDSBT Blocks</h2>
			<div class="wdsbt-showcase-category">
				<div role="group" class="wp-block-accordion">
					<div class="wp-block-accordion-item">
						<h2 class="wp-block-accordion-heading wdsbt-showcase-category-title">
							<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
								<span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $category_labels['wdsbt'] ); ?> (<?php echo esc_html( count( $organized_blocks['wdsbt'] ) ); ?>)</span>
								<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
							</button>
						</h2>
						<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
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
								add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								$allowed_html           = wp_kses_allowed_html( 'post' );
								$allowed_html['input']  = array(
									'type'             => true,
									'name'             => true,
									'value'            => true,
									'placeholder'      => true,
									'required'         => true,
									'id'               => true,
									'class'            => true,
									'aria-label'       => true,
									'aria-labelledby'  => true,
									'aria-describedby' => true,
								);
								$allowed_html['button'] = array(
									'type'            => true,
									'class'           => true,
									'aria-label'      => true,
									'aria-labelledby' => true,
								);
								$allowed_html['form']   = array(
									'action' => true,
									'method' => true,
									'class'  => true,
									'role'   => true,
								);
								$allowed_html['label']  = array(
									'for'   => true,
									'class' => true,
								);
								echo wp_kses( $block_html, $allowed_html );
								remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
								?>
							</div>
						</div>

					<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $organized_blocks['other'] ) ) : ?>
			<h2 class="wdsbt-showcase-section-heading">Third-Party Blocks</h2>
			<div class="wdsbt-showcase-category">
				<div role="group" class="wp-block-accordion">
					<div class="wp-block-accordion-item">
						<h2 class="wp-block-accordion-heading wdsbt-showcase-category-title">
							<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
								<span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $category_labels['other'] ); ?> (<?php echo esc_html( count( $organized_blocks['other'] ) ); ?>)</span>
								<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
							</button>
						</h2>
						<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
							<div class="wdsbt-showcase-blocks">
					<?php foreach ( $organized_blocks['other'] as $block_name => $block_type ) : ?>
						<?php
						$block_html = render_block_for_showcase( $block_name, $block_type );
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
								<?php if ( ! empty( $block_html ) ) : ?>
									<?php
									add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
									$allowed_html           = wp_kses_allowed_html( 'post' );
									$allowed_html['input']  = array(
										'type'             => true,
										'name'             => true,
										'value'            => true,
										'placeholder'      => true,
										'required'         => true,
										'id'               => true,
										'class'            => true,
										'aria-label'       => true,
										'aria-labelledby'  => true,
										'aria-describedby' => true,
									);
									$allowed_html['button'] = array(
										'type'            => true,
										'class'           => true,
										'aria-label'      => true,
										'aria-labelledby' => true,
									);
									$allowed_html['form']   = array(
										'action' => true,
										'method' => true,
										'class'  => true,
										'role'   => true,
									);
									$allowed_html['label']  = array(
										'for'   => true,
										'class' => true,
									);
									echo wp_kses( $block_html, $allowed_html );
									remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
									?>
								<?php else : ?>
									<p><em>This block type cannot be previewed in the showcase. It may require specific context or configuration to render.</em></p>
								<?php endif; ?>
							</div>
						</div>

					<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>

	</div>

	<script>
		(function() {
			document.addEventListener('DOMContentLoaded', function() {
				const accordionToggles = document.querySelectorAll('.wdsbt-block-showcase .wp-block-accordion-heading__toggle');

				accordionToggles.forEach(function(toggle) {
					toggle.addEventListener('click', function() {
						const panel = this.closest('.wp-block-accordion-item').querySelector('.wp-block-accordion-panel');
						const isExpanded = this.getAttribute('aria-expanded') === 'true';

						this.setAttribute('aria-expanded', !isExpanded);

						if (isExpanded) {
							panel.setAttribute('aria-hidden', 'true');
							panel.style.display = 'none';
						} else {
							panel.setAttribute('aria-hidden', 'false');
							panel.style.display = 'block';
						}
					});
				});
			});
		})();
	</script>

	<?php
	return ob_get_clean();
}

