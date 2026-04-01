<?php
/**
 * Merge detected fonts into theme.json typography.fontFamilies.
 *
 * Scans assets/fonts and build/fonts. When theme.json already defines a family
 * by slug, merges scanned fontFace src paths while preserving manual settings
 * (e.g. variable font fontWeight "100 900"). New folder slugs are appended.
 * If no fontFamilies exist yet, writes the scanned set only.
 *
 * Usage: php tools/generate-theme-json.php
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Scan directory for font files.
 *
 * @param string $directory Directory to scan.
 * @return array Array of font files.
 */
function scan_font_directory( $directory ) {
	$fonts     = array();
	$theme_dir = dirname( __DIR__, 1 );
	$full_path = $theme_dir . '/' . $directory;

	if ( ! is_dir( $full_path ) ) {
		return $fonts;
	}

	$iterator = new \RecursiveIteratorIterator(
		new \RecursiveDirectoryIterator( $full_path, \RecursiveDirectoryIterator::SKIP_DOTS )
	);

	foreach ( $iterator as $file ) {
		if ( $file->isFile() && in_array( strtolower( $file->getExtension() ), array( 'woff2', 'woff', 'ttf', 'otf' ), true ) ) {
			$relative_path = str_replace( $theme_dir . '/', '', $file->getPathname() );
			$filename      = $file->getBasename();
			$font_metadata = parse_font_filename( $filename );

			$folder_name = basename( dirname( $file->getPathname() ) );

			$variant_key = $font_metadata['family'] . '-' . $font_metadata['weight'] . '-' . $font_metadata['style'];

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

if ( ! function_exists( __NAMESPACE__ . '\\parse_font_filename' ) ) {
	/**
	 * Parse font metadata from filename.
	 *
	 * @param string $filename Font filename.
	 * @return array Font metadata.
	 */
	function parse_font_filename( $filename ) {
		$metadata = array(
			'family' => 'Unknown',
			'weight' => '400',
			'style'  => 'normal',
		);

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

		$style_patterns = array(
			'italic'  => 'italic',
			'oblique' => 'oblique',
		);

		$filename_without_ext = preg_replace( '/\.(woff2?|ttf|otf)$/i', '', $filename );
		$lowercase_filename   = strtolower( $filename_without_ext );

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

		$parts           = preg_split( '/[-_\s]+/', $filename_without_ext );
		$family_parts    = array();
		$weight_keywords = array( 'thin', 'extralight', 'light', 'regular', 'normal', 'medium', 'semibold', 'bold', 'extrabold', 'black', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
		$style_keywords  = array( 'italic', 'oblique' );

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
 * Merge one theme.json font family with scanned data.
 *
 * Preserves name, slug, fontFamily, and variable-font fontWeight ranges; updates src from disk.
 *
 * @param array $existing Family from theme.json.
 * @param array $scanned  Family from scan/group_fonts_by_family.
 * @return array Merged family.
 */
function merge_font_family_with_scan( array $existing, array $scanned ) {
	$out = $existing;

	if ( empty( $scanned['fontFace'] ) || ! is_array( $scanned['fontFace'] ) ) {
		return $out;
	}

	$ex_faces = isset( $existing['fontFace'] ) && is_array( $existing['fontFace'] ) ? $existing['fontFace'] : array();
	$sc_faces = $scanned['fontFace'];

	// Single variable face: keep fontWeight range (e.g. "100 900"), refresh src from scan.
	if ( 1 === count( $ex_faces ) && 1 === count( $sc_faces ) ) {
		$ew = isset( $ex_faces[0]['fontWeight'] ) ? trim( (string) $ex_faces[0]['fontWeight'] ) : '';
		if ( '' !== $ew && preg_match( '/^\d+\s+\d+$/', $ew ) ) {
			$face = $ex_faces[0];
			if ( isset( $sc_faces[0]['src'] ) ) {
				$face['src'] = $sc_faces[0]['src'];
			}
			if ( isset( $sc_faces[0]['fontFamily'] ) ) {
				$face['fontFamily'] = $sc_faces[0]['fontFamily'];
			}
			if ( isset( $sc_faces[0]['fontStyle'] ) ) {
				$face['fontStyle'] = $sc_faces[0]['fontStyle'];
			}
			$out['fontFace'] = array( $face );
			return $out;
		}
	}

	$out['fontFace'] = $sc_faces;

	return $out;
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

if ( ! function_exists( __NAMESPACE__ . '\\get_font_slug' ) ) {
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
}

/**
 * Generate theme.json with detected fonts.
 */
function generate_theme_json() {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( "\nScanning for fonts...\n" );

	$base_theme_json_path = dirname( __DIR__, 1 ) . '/theme.json';
	$base_theme_json      = array();

	if ( file_exists( $base_theme_json_path ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- CLI tool reading local file
		$base_theme_json = json_decode( file_get_contents( $base_theme_json_path ), true );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "Loaded base theme.json\n" );
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "No base theme.json found, creating new one\n" );
	}

	$build_fonts  = scan_font_directory( 'build/fonts' );
	$assets_fonts = scan_font_directory( 'assets/fonts' );
	$all_fonts    = array_merge( $build_fonts, $assets_fonts );
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

	if ( ! isset( $base_theme_json['settings']['typography'] ) ) {
		$base_theme_json['settings']['typography'] = array();
	}

	$scanned_list    = array_values( $font_families );
	$scanned_by_slug = array();
	foreach ( $scanned_list as $fam ) {
		if ( ! empty( $fam['slug'] ) && is_string( $fam['slug'] ) ) {
			$scanned_by_slug[ $fam['slug'] ] = $fam;
		}
	}

	$existing_families = $base_theme_json['settings']['typography']['fontFamilies'] ?? array();
	if ( ! is_array( $existing_families ) ) {
		$existing_families = array();
	}

	if ( ! empty( $scanned_list ) ) {
		if ( empty( $existing_families ) ) {
			$base_theme_json['settings']['typography']['fontFamilies'] = $scanned_list;
		} else {
			$merged       = array();
			$used_scanned = array();
			foreach ( $existing_families as $fam ) {
				if ( ! is_array( $fam ) ) {
					continue;
				}
				$slug = isset( $fam['slug'] ) && is_string( $fam['slug'] ) ? $fam['slug'] : '';
				if ( '' !== $slug && isset( $scanned_by_slug[ $slug ] ) ) {
					$merged[]              = merge_font_family_with_scan( $fam, $scanned_by_slug[ $slug ] );
					$used_scanned[ $slug ] = true;
				} else {
					$merged[] = $fam;
				}
			}
			foreach ( $scanned_list as $fam ) {
				$slug = isset( $fam['slug'] ) && is_string( $fam['slug'] ) ? $fam['slug'] : '';
				if ( '' !== $slug && empty( $used_scanned[ $slug ] ) ) {
					$merged[]              = $fam;
					$used_scanned[ $slug ] = true;
				}
			}
			$base_theme_json['settings']['typography']['fontFamilies'] = $merged;
		}
	}
	$font_count   = 0;
	$family_count = 0;
	if ( isset( $base_theme_json['settings']['typography']['fontFamilies'] ) ) {
		$family_count = count( $base_theme_json['settings']['typography']['fontFamilies'] );
		foreach ( $base_theme_json['settings']['typography']['fontFamilies'] as $family ) {
			$font_count += count( $family['fontFace'] );
		}
	}

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( "\nDetection Results:\n" );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( "Font Families: %d\n", $family_count );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( "Total Fonts: %d\n", $font_count );

	if ( $family_count > 0 ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "\nDetected Families:\n" );
		foreach ( $base_theme_json['settings']['typography']['fontFamilies'] as $family ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( "- %s: %d variants\n", $family['name'], count( $family['fontFace'] ) );
		}
	}

	$output_path = dirname( __DIR__, 1 ) . '/theme.json';

	// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- CLI tool
	$json_content = json_encode( $base_theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file
	if ( file_put_contents( $output_path, $json_content ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "\nSuccessfully generated theme.json with detected fonts\n" );
		$theme_dir  = dirname( __DIR__, 1 );
		$style_path = $theme_dir . '/build/css/style.css';
		if ( file_exists( $style_path ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( "Cache version: %s\n", (string) filemtime( $style_path ) );
		}
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "\nFailed to write theme.json\n" );
	}
}

// Run the generator.
generate_theme_json();
