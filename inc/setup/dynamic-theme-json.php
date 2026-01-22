<?php
/**
 * Dynamic theme.json generation with automatic font detection.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Get all available font files from a directory.
 *
 * @param string $directory Directory to scan.
 * @return array Array of font files with their metadata.
 */
function scan_font_directory( $directory ) {
	$fonts     = array();
	$theme_dir = get_template_directory();
	$full_path = $theme_dir . '/' . $directory;

	if ( ! is_dir( $full_path ) ) {
		return $fonts;
	}

	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $full_path, RecursiveDirectoryIterator::SKIP_DOTS )
	);

	foreach ( $iterator as $file ) {
		if ( $file->isFile() && in_array( $file->getExtension(), array( 'woff', 'woff2', 'ttf', 'otf' ), true ) ) {
			$relative_path = str_replace( $theme_dir . '/', '', $file->getPathname() );

			// Skip if the path contains duplicate directory names (like build/fonts/assets/fonts).
			if ( preg_match( '/(build\/fonts\/assets\/fonts|assets\/fonts\/build\/fonts)/', $relative_path ) ) {
				continue;
			}

			$filename = $file->getBasename( '.' . $file->getExtension() );

			// Parse font metadata from filename.
			$font_metadata = parse_font_filename( $filename );

			// Get folder name as slug (body, headline, mono, etc.).
			$folder_name = basename( dirname( $file->getPathname() ) );

			// Create a unique key for this font variant.
			$variant_key = $font_metadata['family'] . '-' . $font_metadata['weight'] . '-' . $font_metadata['style'];

			// Only add if we haven't seen this variant before, or if this is from build/ and we've only seen it in assets/.
			if ( ! isset( $fonts[ $variant_key ] ) ||
				( strpos( $relative_path, 'build/' ) === 0 && strpos( $fonts[ $variant_key ]['path'], 'assets/' ) === 0 ) ) {
				$fonts[ $variant_key ] = array(
					'path'      => $relative_path,
					'filename'  => $filename,
					'extension' => $file->getExtension(),
					'family'    => $font_metadata['family'],
					'weight'    => $font_metadata['weight'],
					'style'     => $font_metadata['style'],
					'slug'      => $folder_name,
				);
			}
		}
	}

	return array_values( $fonts );
}

/**
 * Parse font metadata from filename.
 *
 * @param string $filename Font filename.
 * @return array Font metadata.
 */
function parse_font_filename( $filename ) {
	// Default values.
	$metadata = array(
		'family' => 'Unknown',
		'weight' => '400',
		'style'  => 'normal',
	);

	// Common weight patterns with exact matches.
	$weight_patterns = array(
		'-100'       => '100',
		'-200'       => '200',
		'-300'       => '300',
		'-regular'   => '400',
		'-normal'    => '400',
		'-400'       => '400',
		'-500'       => '500',
		'-600'       => '600',
		'-700'       => '700',
		'-800'       => '800',
		'-900'       => '900',
		'thin'       => '100',
		'extralight' => '200',
		'light'      => '300',
		'regular'    => '400',
		'medium'     => '500',
		'semibold'   => '600',
		'bold'       => '700',
		'extrabold'  => '800',
		'black'      => '900',
	);

	// Common style patterns.
	$style_patterns = array(
		'italic'  => 'italic',
		'oblique' => 'oblique',
	);

	$filename_without_ext = preg_replace( '/\.(woff2?|ttf|otf)$/i', '', $filename );
	$lowercase_filename = strtolower( $filename_without_ext );

	foreach ( $weight_patterns as $pattern => $weight ) {
		if ( strpos( $lowercase_filename, $pattern ) !== false ) {
			$metadata['weight'] = $weight;
			break;
		}
	}

	foreach ( $style_patterns as $pattern => $style ) {
		if ( strpos( $lowercase_filename, $pattern ) !== false ) {
			$metadata['style'] = $style;
			break;
		}
	}

	$parts = preg_split( '/[-_\s]+/', $filename_without_ext );
	$family_parts = array();
	$weight_keywords = array( 'thin', 'extralight', 'light', 'regular', 'normal', 'medium', 'semibold', 'bold', 'extrabold', 'black', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
	$style_keywords = array( 'italic', 'oblique' );
	
	foreach ( $parts as $part ) {
		$lower_part = strtolower( $part );
		
		if ( in_array( $lower_part, $weight_keywords, true ) || in_array( $lower_part, $style_keywords, true ) ) {
			continue;
		}
		
		if ( preg_match( '/^v\d+|^latin|^\d+$/', $lower_part ) ) {
			continue;
		}
		
		$family_parts[] = $part;
	}
	
	if ( ! empty( $family_parts ) ) {
		$metadata['family'] = ucwords( implode( ' ', $family_parts ) );
	} elseif ( ! empty( $parts[0] ) ) {
		$metadata['family'] = ucwords( str_replace( array( '-', '_' ), ' ', $parts[0] ) );
	}

	return $metadata;
}

/**
 * Group fonts by family.
 *
 * @param array $fonts Array of font files.
 * @return array Fonts grouped by family.
 */
function group_fonts_by_family( $fonts ) {
	$grouped = array();

	foreach ( $fonts as $font ) {
		$family = $font['family'];
		$slug   = $font['slug'] ?? get_font_slug( $family );

		if ( ! isset( $grouped[ $family ] ) ) {
			$grouped[ $family ] = array(
				'name'       => $family,
				'slug'       => $slug,
				'fontFamily' => $family . ', sans-serif',
				'fontFace'   => array(),
			);
		}

		$grouped[ $family ]['fontFace'][] = array(
			'fontFamily' => $family,
			'fontStyle'  => $font['style'],
			'fontWeight' => $font['weight'],
			'src'        => array( "file:./{$font['path']}" ),
		);
	}

	return $grouped;
}

/**
 * Sanitize title (simple version without WordPress dependency).
 *
 * @param string $title Title to sanitize.
 * @return string Sanitized title.
 */
function sanitize_title( $title ) {
	$title = strtolower( $title );
	$title = preg_replace( '/[^a-z0-9\s-]/', '', $title );
	$title = preg_replace( '/[\s-]+/', '-', $title );
	return trim( $title, '-' );
}

/**
 * Fallback function to generate slug from family name.
 * This should only be used if slug is not provided in font array.
 *
 * @param string $family Font family name.
 * @return string Standardized slug.
 */
function get_font_slug( $family ) {
	return sanitize_title( $family );
}

/**
 * Filter theme.json data to include dynamically detected fonts.
 *
 * @param \WP_Theme_JSON_Data $theme_json_data Theme JSON data object.
 * @return \WP_Theme_JSON_Data Modified theme JSON data.
 */
function filter_theme_json_data( $theme_json_data ) {
	// Get the current theme.json content.
	$theme_json = $theme_json_data->get_data();

	$build_fonts  = scan_font_directory( 'build/fonts' );
	$assets_fonts = scan_font_directory( 'assets/fonts' );
	$all_fonts = array_merge( $build_fonts, $assets_fonts );
	$unique_fonts = array();
	$seen_paths   = array();

	foreach ( $all_fonts as $font ) {
		$key = $font['family'] . '-' . $font['weight'] . '-' . $font['style'];
		if ( ! isset( $seen_paths[ $key ] ) ) {
			$unique_fonts[]     = $font;
			$seen_paths[ $key ] = true;
		}
	}

	$font_families = group_fonts_by_family( $unique_fonts );

	if ( ! isset( $theme_json['settings']['typography'] ) ) {
		$theme_json['settings']['typography'] = array();
	}

	if ( ! empty( $font_families ) ) {
		$theme_json['settings']['typography']['fontFamilies'] = array_values( $font_families );
	}

	// Create new theme JSON data object with updated content.
	$updated_theme_json = new \WP_Theme_JSON_Data( $theme_json );

	return $updated_theme_json;
}

/**
 * Initialize dynamic theme.json functionality.
 */
function init_dynamic_theme_json() {
	add_filter( 'wp_theme_json_data_theme', __NAMESPACE__ . '\filter_theme_json_data' );
}
add_action( 'init', __NAMESPACE__ . '\init_dynamic_theme_json' );

/**
 * Get font detection debug HTML.
 *
 * @return string Font detection debug HTML.
 */
function get_font_detection_debug_html() {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return '';
	}

	$build_fonts   = scan_font_directory( 'build/fonts' );
	$assets_fonts  = scan_font_directory( 'assets/fonts' );
	$font_families = group_fonts_by_family( array_merge( $build_fonts, $assets_fonts ) );

	ob_start();
	?>
	<div style="margin: 2em 0 1em; padding: 1em; background: #f0f6fc; border-left: 4px solid #2271b1;">
		<h3 style="margin-top: 0;"><?php esc_html_e( 'Font Detection Debug', 'wdsbt' ); ?></h3>
		<p>
			<strong><?php esc_html_e( 'Build fonts found:', 'wdsbt' ); ?></strong> <?php echo count( $build_fonts ); ?><br>
			<strong><?php esc_html_e( 'Assets fonts found:', 'wdsbt' ); ?></strong> <?php echo count( $assets_fonts ); ?>
		</p>
		<?php if ( ! empty( $font_families ) ) : ?>
			<p><strong><?php esc_html_e( 'Font families detected:', 'wdsbt' ); ?></strong></p>
			<ul style="margin-left: 1.5em; margin-bottom: 1em;">
			<?php foreach ( $font_families as $family => $config ) : ?>
				<li>
					<?php echo esc_html( $family ); ?>: <?php echo count( $config['fontFace'] ); ?> <?php esc_html_e( 'variants', 'wdsbt' ); ?>
					<ul style="margin-left: 1.5em;">
						<?php foreach ( $config['fontFace'] as $face ) : ?>
							<li><?php echo esc_html( "{$face['fontWeight']} {$face['fontStyle']}: {$face['src'][0]}" ); ?></li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p><em><?php esc_html_e( 'No font families detected.', 'wdsbt' ); ?></em></p>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Debug function to log detected fonts in development (legacy - outputs as admin notice).
 */
function debug_font_detection() {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! is_admin() ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || 'tools_page_wdsbt-settings' !== $screen->id ) {
		return;
	}

	// Don't output here - it will be shown at the bottom of the settings page.
}
