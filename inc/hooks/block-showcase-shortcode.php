<?php
/**
 * Block Showcase shortcode.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers the block showcase shortcode.
 *
 * @return void
 */
function register_block_showcase_shortcode() {
	add_shortcode( 'wdsbt_block_showcase', __NAMESPACE__ . '\render_block_showcase_shortcode' );
}
add_action( 'init', __NAMESPACE__ . '\register_block_showcase_shortcode' );

/**
 * Adds data: for showcase previews.
 *
 * @param array $protocols Allowed protocols.
 * @return array Modified protocols.
 */
function allow_data_uris_in_showcase( $protocols ) {
	$protocols[] = 'data';
	return $protocols;
}

/**
 * Gets the allowed HTML for block showcase previews.
 *
 * @return array Allowed HTML.
 */
function get_block_showcase_preview_allowed_html() {
	$allowed_html           = wp_kses_allowed_html( 'post' );
	$allowed_html['figure'] = array(
		'class' => true,
		'style' => true,
	);
	$allowed_html['img']    = array(
		'src'    => true,
		'alt'    => true,
		'width'  => true,
		'height' => true,
	);
	$allowed_html['div']    = array(
		'class' => true,
		'style' => true,
	);
	// Post context has no <input>; keep a broad list for search and other form blocks.
	$allowed_html['input'] = array(
		'type'             => true,
		'name'             => true,
		'value'            => true,
		'placeholder'      => true,
		'required'         => true,
		'id'               => true,
		'class'            => true,
		'style'            => true,
		'disabled'         => true,
		'readonly'         => true,
		'size'             => true,
		'maxlength'        => true,
		'min'              => true,
		'max'              => true,
		'step'             => true,
		'pattern'          => true,
		'autocomplete'     => true,
		'tabindex'         => true,
		'aria-label'       => true,
		'aria-labelledby'  => true,
		'aria-describedby' => true,
		'aria-hidden'      => true,
		'aria-expanded'    => true,
		'aria-controls'    => true,
		'data-*'           => true,
	);
	// Do not replace post defaults for <button> or <label>: core/search applies inline color styles
	// and currentColor-based SVG icons need those attributes preserved.
	$form_base              = isset( $allowed_html['form'] ) && is_array( $allowed_html['form'] ) ? $allowed_html['form'] : array();
	$allowed_html['form']   = array_merge(
		array(
			'action'         => true,
			'accept'         => true,
			'accept-charset' => true,
			'enctype'        => true,
			'method'         => true,
			'name'           => true,
			'target'         => true,
		),
		$form_base,
		array(
			'class'  => true,
			'role'   => true,
			'style'  => true,
			'id'     => true,
			'data-*' => true,
		)
	);
	$allowed_html['iframe'] = array(
		'src'             => true,
		'width'           => true,
		'height'          => true,
		'frameborder'     => true,
		'allowfullscreen' => true,
		'title'           => true,
		'class'           => true,
		'style'           => true,
		'loading'         => true,
		'sandbox'         => true,
		'allow'           => true,
	);

	// core/social-link (and similar) render inline SVG icons; post context strips svg/path by default.
	$allowed_html['svg']      = array(
		'class'       => true,
		'xmlns'       => true,
		'width'       => true,
		'height'      => true,
		'viewbox'     => true,
		'version'     => true,
		'aria-hidden' => true,
		'focusable'   => true,
		'fill'        => true,
		'role'        => true,
		'style'       => true,
	);
	$allowed_html['path']     = array(
		'class'           => true,
		'd'               => true,
		'fill'            => true,
		'fill-rule'       => true,
		'clip-rule'       => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
		'opacity'         => true,
		'transform'       => true,
	);
	$allowed_html['circle']   = array(
		'class'     => true,
		'cx'        => true,
		'cy'        => true,
		'r'         => true,
		'fill'      => true,
		'stroke'    => true,
		'transform' => true,
	);
	$allowed_html['rect']     = array(
		'class'     => true,
		'x'         => true,
		'y'         => true,
		'width'     => true,
		'height'    => true,
		'rx'        => true,
		'ry'        => true,
		'fill'      => true,
		'stroke'    => true,
		'transform' => true,
	);
	$allowed_html['g']        = array(
		'class'     => true,
		'fill'      => true,
		'transform' => true,
	);
	$allowed_html['polygon']  = array(
		'class'     => true,
		'points'    => true,
		'fill'      => true,
		'stroke'    => true,
		'transform' => true,
	);
	$allowed_html['polyline'] = array(
		'class'     => true,
		'points'    => true,
		'fill'      => true,
		'stroke'    => true,
		'transform' => true,
	);
	$allowed_html['line']     = array(
		'class'     => true,
		'x1'        => true,
		'y1'        => true,
		'x2'        => true,
		'y2'        => true,
		'stroke'    => true,
		'transform' => true,
	);

	return $allowed_html;
}

/**
 * Echoes block HTML through wp_kses() with showcase rules.
 *
 * @param string $block_html Raw block HTML.
 * @return void
 */
function echo_block_showcase_preview_html( $block_html ) {
	add_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
	echo wp_kses( $block_html, get_block_showcase_preview_allowed_html() );
	remove_filter( 'kses_allowed_protocols', __NAMESPACE__ . '\allow_data_uris_in_showcase' );
}

/**
 * Prints block style and variation previews (block.json `styles` and `variations`).
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type  Registered block type.
 * @return void
 */
function render_block_showcase_variations_section( $block_name, $block_type ) {
	$previews = get_block_showcase_style_and_variation_previews( $block_name, $block_type );
	if ( empty( $previews ) ) {
		return;
	}
	?>
	<details class="wdsbt-showcase-variations">
		<summary class="wdsbt-showcase-variations__summary">
			<?php
			echo esc_html(
				sprintf(
					/* translators: %d: number of block styles plus variations */
					__( 'Styles & variations (%d)', 'wdsbt' ),
					count( $previews )
				)
			);
			?>
		</summary>
		<div class="wdsbt-showcase-variations__grid">
			<?php foreach ( $previews as $preview ) : ?>
				<?php
				$kind       = isset( $preview['kind'] ) ? (string) $preview['kind'] : 'variation';
				$code       = ( 'style' === $kind ) ? 'is-style-' . $preview['name'] : $preview['name'];
				$kind_label = ( 'style' === $kind )
					? esc_html__( 'Style', 'wdsbt' )
					: esc_html__( 'Variation', 'wdsbt' );
				?>
				<div class="wdsbt-showcase-variation-card">
					<div class="wdsbt-showcase-variation-card__meta">
						<span class="wdsbt-showcase-variation-card__kind"><?php echo esc_html( $kind_label ); ?></span>
						<h5 class="wdsbt-showcase-variation-card__title"><?php echo esc_html( $preview['title'] ); ?></h5>
						<code class="wdsbt-showcase-variation-card__name"><?php echo esc_html( $code ); ?></code>
					</div>
					<div class="wdsbt-showcase-variation-card__preview">
						<?php echo_block_showcase_preview_html( $preview['html'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</details>
	<?php
}

/**
 * Renders the block showcase shortcode.
 *
 * @param array  $atts    Shortcode attributes. Unused but required for shortcode signature.
 * @param string $content Shortcode content. Unused but required for shortcode signature.
 * @return string Rendered HTML.
 */
function render_block_showcase_shortcode( $atts = array(), $content = '' ) {
	unset( $atts, $content );
	if ( ! current_user_can( 'manage_options' ) ) {
		return '';
	}

	$GLOBALS['wdsbt_block_showcase_rendered'] = true;

	$organized_blocks = get_all_registered_blocks();

	$wp_categories   = get_block_categories( get_post() );
	$category_labels = array();
	foreach ( $wp_categories as $category ) {
		$category_labels[ $category['slug'] ] = $category['title'];
	}

	$category_labels['other'] = 'Other Core Blocks';
	$category_labels['wdsbt'] = 'WDS BT Custom Blocks';

	$theme_color_palette = get_theme_json_color_palette();

	ob_start();
	?>

	<div class="wdsbt-block-showcase">
		<?php if ( ! empty( $theme_color_palette ) ) : ?>
			<h3 class="wdsbt-showcase-section-heading"><?php echo esc_html__( 'Theme colors', 'wdsbt' ); ?></h3>
			<div class="wdsbt-showcase-category">
				<div role="group" class="wp-block-accordion">
					<div class="wp-block-accordion-item">
						<div class="wp-block-accordion-heading wdsbt-showcase-category-title">
							<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
								<span class="wp-block-accordion-heading__toggle-title">
									<?php
									echo esc_html(
										sprintf(
											/* translators: %d: number of theme colors in palette */
											__( 'Theme colors (%d)', 'wdsbt' ),
											count( $theme_color_palette )
										)
									);
									?>
								</span>
								<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
							</button>
						</div>
						<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
							<div class="wdsbt-showcase-colors">
								<?php foreach ( $theme_color_palette as $color_entry ) : ?>
									<?php
									if ( empty( $color_entry['slug'] ) || ! isset( $color_entry['color'] ) ) {
										continue;
									}
									$color_value = is_string( $color_entry['color'] ) ? $color_entry['color'] : '';
									if ( '' === $color_value ) {
										continue;
									}
									$color_name   = isset( $color_entry['name'] ) && is_string( $color_entry['name'] ) ? $color_entry['name'] : $color_entry['slug'];
									$display_val  = showcase_color_display_value( $color_value );
									$preview_fg   = showcase_color_contrast_foreground( $color_value );
									$aria_preview = $color_name . ' — ' . $display_val;
									?>
									<div
										class="wdsbt-showcase-color-swatch"
										style="<?php echo esc_attr( '--wdsbt-swatch-color:' . $color_value . ';' ); ?>"
									>
										<span class="wdsbt-showcase-color-header"><?php echo esc_html( $color_entry['slug'] ); ?></span>
										<span
											class="wdsbt-showcase-color-preview"
											role="img"
											aria-label="<?php echo esc_attr( $aria_preview ); ?>"
										>
											<code
												class="wdsbt-showcase-color-hex"
												style="<?php echo esc_attr( 'color:' . $preview_fg . ';' ); ?>"
											><?php echo esc_html( $display_val ); ?></code>
										</span>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php
		$core_blocks_by_category = array();
		$rendered_blocks_cache   = array();
		foreach ( $organized_blocks['core'] as $block_name => $block_type ) {
			$block_html = render_block_for_showcase( $block_name, $block_type );
			if ( empty( $block_html ) ) {
				continue;
			}
			$category = get_block_category( $block_name, $block_type );
			if ( ! isset( $core_blocks_by_category[ $category ] ) ) {
				$core_blocks_by_category[ $category ] = array();
			}
			$core_blocks_by_category[ $category ][ $block_name ] = $block_type;
			$rendered_blocks_cache[ $block_name ]                = $block_html;
		}

		$all_categories      = array_keys( $core_blocks_by_category );
		$default_order       = array( 'text', 'media', 'design', 'widgets', 'theme', 'embeds' );
		$core_category_order = array_merge( $default_order, array_diff( $all_categories, $default_order ) );

		if ( ! empty( $organized_blocks['core'] ) ) :
			?>
			<h3 class="wdsbt-showcase-section-heading">Core Blocks</h3>
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
						$block_html = isset( $rendered_blocks_cache[ $block_name ] ) ? $rendered_blocks_cache[ $block_name ] : render_block_for_showcase( $block_name, $block_type );
						?>

						<div class="wdsbt-showcase-block-card" id="<?php echo esc_attr( get_block_showcase_anchor_id( $block_name ) ); ?>">
							<div class="wdsbt-showcase-block-meta">
								<h4 class="wdsbt-showcase-block-title"><?php echo esc_html( get_block_display_name( $block_name ) ); ?></h4>
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
								<?php echo_block_showcase_preview_html( $block_html ); ?>
							</div>
							<?php render_block_showcase_variations_section( $block_name, $block_type ); ?>
						</div>

					<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

		<?php if ( ! empty( $organized_blocks['wdsbt'] ) ) : ?>
			<h3 class="wdsbt-showcase-section-heading">WDSBT Blocks</h3>
			<div class="wdsbt-showcase-category">
				<div role="group" class="wp-block-accordion">
					<div class="wp-block-accordion-item">
						<h3 class="wp-block-accordion-heading wdsbt-showcase-category-title">
							<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
								<span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $category_labels['wdsbt'] ); ?> (<?php echo esc_html( count( $organized_blocks['wdsbt'] ) ); ?>)</span>
								<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
							</button>
						</h3>
						<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
							<div class="wdsbt-showcase-blocks">
					<?php foreach ( $organized_blocks['wdsbt'] as $block_name => $block_type ) : ?>
						<?php
						$block_html = render_block_for_showcase( $block_name, $block_type );
						if ( empty( $block_html ) ) {
							continue;
						}
						?>

						<div class="wdsbt-showcase-block-card" id="<?php echo esc_attr( get_block_showcase_anchor_id( $block_name ) ); ?>">
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
								<?php echo_block_showcase_preview_html( $block_html ); ?>
							</div>
							<?php render_block_showcase_variations_section( $block_name, $block_type ); ?>
						</div>

					<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php
		$third_party_namespaces = array_diff( array_keys( $organized_blocks ), array( 'core', 'wdsbt' ) );
		if ( ! empty( $third_party_namespaces ) ) :
			?>
			<h3 class="wdsbt-showcase-section-heading"><?php echo esc_html( 'Third Party Blocks' ); ?></h3>
			<?php
			foreach ( $third_party_namespaces as $namespace ) :
				if ( empty( $organized_blocks[ $namespace ] ) ) {
					continue;
				}
				$plugin_name = ucwords( str_replace( array( '-', '_' ), ' ', $namespace ) );
				?>
				<div class="wdsbt-showcase-category">
					<div role="group" class="wp-block-accordion">
						<div class="wp-block-accordion-item">
							<h4 class="wp-block-accordion-heading wdsbt-showcase-category-title">
								<button class="wp-block-accordion-heading__toggle" type="button" aria-expanded="false">
									<span class="wp-block-accordion-heading__toggle-title"><?php echo esc_html( $plugin_name ); ?> Blocks (<?php echo esc_html( count( $organized_blocks[ $namespace ] ) ); ?>)</span>
									<span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span>
								</button>
							</h4>
							<div role="region" class="wp-block-accordion-panel" aria-hidden="true">
								<div class="wdsbt-showcase-blocks">
						<?php foreach ( $organized_blocks[ $namespace ] as $block_name => $block_type ) : ?>
							<?php
							$block_html = render_block_for_showcase( $block_name, $block_type );
							?>

						<div class="wdsbt-showcase-block-card" id="<?php echo esc_attr( get_block_showcase_anchor_id( $block_name ) ); ?>">
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
									<?php echo_block_showcase_preview_html( $block_html ); ?>
								<?php else : ?>
									<p><em>This block type cannot be previewed in the showcase. It may require specific context or configuration to render.</em></p>
								<?php endif; ?>
							</div>
							<?php render_block_showcase_variations_section( $block_name, $block_type ); ?>
						</div>

						<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<?php
	return ob_get_clean();
}

