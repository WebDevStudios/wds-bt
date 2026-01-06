<?php
/**
 * WDSBT Font Helpers.
 *
 * Low-level scanning, deduplication, grouping, and debug printing for fonts.
 * All directories, output formats, and paths come from font-pipeline-config.php.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

if ( php_sapi_name() !== 'cli' && ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly in a web request.
}


// Load config
require_once __DIR__ . '/font-pipeline-config.php';



if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_get_font_role_slug' ) ) {
	function wdsbt_get_font_role_slug( $family_or_path ) {

		// Slug mapping is intentionally opinionated
		// Unsure the purpose of this - Potentially remove in future versions!
		static $slug_mapping = array(
			'Oxygen'      => 'body',
			'Inter'       => 'headline',
			'Roboto Mono' => 'mono',
		);

		if ( is_array( $family_or_path ) ) {
			if ( ! empty( $family_or_path['path'] ) ) {
				$candidate = $family_or_path['path'];
			} elseif ( ! empty( $family_or_path['family'] ) ) {
				$candidate = $family_or_path['family'];
			} else {
				$candidate = '';
			}
		} else {
			$candidate = (string) $family_or_path;
		}

		// Normalize separators
		$normalized = str_replace( '\\', '/', $candidate );

		// Folder-based slug
		if ( false !== strpos( $normalized, '/fonts/' ) ) {
			$after = explode( '/fonts/', $normalized, 2 )[1];
			$segments = explode( '/', trim( $after, '/' ) );
			if ( ! empty( $segments[0] ) ) {
				return wdsbt_sanitize_title( $segments[0] );
			}
		}

		// Family name mapping
		foreach ( $slug_mapping as $key => $value ) {
			if ( 0 === strcasecmp( $candidate, $key ) ) {
				// printf( "Default mapping found and used for " . $candidate . "\n" );
				return $value;
			}
		}

		// Fallback: strip extension from filename and sanitize
		$base = $normalized;
		if ( false !== strpos( $base, '/' ) ) {
			$base = basename( $base );
		}
		$base = preg_replace( '/\.[^.]+$/', '', $base );

		return ! empty( $base ) ? wdsbt_sanitize_title( $base ) : 'unknown';
	}
}




/**
 * Sanitize a string into a URL/title-safe slug.
 *
 * @param string $text
 * @return string
 */
if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_sanitize_title' ) ) {
	function wdsbt_sanitize_title( $text ) {
		$text = strtolower( trim( $text ) );
		$text = preg_replace( '/[^a-z0-9]+/', '-', $text );
		$text = trim( $text, '-' );
		return $text;
	}
}


/**
 * Scan a directory for font files and extract metadata.
 * Low-level helper: no deduplication or path policy.
 *
 * @param string $directory Relative directory to scan.
 * @param string $theme_dir Absolute theme root.
 * @return array List of font files with metadata.
 */
if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_scan_font_directory_raw' ) ) {
	function wdsbt_scan_font_directory_raw( $directory, $theme_dir = null ) {

		$theme_dir = $theme_dir ?: dirname( __DIR__, 1 );
		$fonts     = [];

		$full_path = rtrim( $theme_dir, '/' ) . '/' . trim( $directory, '/' );
		if ( ! is_dir( $full_path ) ) {
			return $fonts;
		}

		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator( $full_path, \RecursiveDirectoryIterator::SKIP_DOTS )
		);

		foreach ( $iterator as $file ) {
			if (
				$file->isFile() &&
				in_array( strtolower( $file->getExtension() ), $GLOBALS['wdsbt_config']['formats'], true )
			)
			{
				$filename = $file->getBasename( '.' . $file->getExtension() );
				$relative_path = str_replace( rtrim( $theme_dir, '/' ) . '/', '', $file->getPathname() );

				$font_metadata = wdsbt_parse_font_meta_from_filename( $filename );

				$fonts[] = [
					'path' => $file->getPathname(),
					'relative_path' => $relative_path,
					'filename'      => $filename,
					'extension'     => $file->getExtension(),
					'family'        => $font_metadata['family'],
					'weight'        => $font_metadata['weight'],
					'style'         => $font_metadata['style'],
				];
			}
		}

		return $fonts;
	}
}


/**
 * Resolve font variants and apply precedence rules.
 *
 * @param array $fonts Raw font list.
 * @return array Resolved font list.
 */
if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_resolve_fonts' ) ) {
	function wdsbt_resolve_fonts( array $fonts ) {

		$resolved = array();

		foreach ( $fonts as $font ) {

			// Skip known bad paths (build/fonts/assets/fonts, etc).
			if (
				isset( $font['relative_path'] ) &&
				preg_match( '/(build\/fonts\/assets\/fonts|assets\/fonts\/build\/fonts)/', $font['relative_path'] )
			) {
				continue;
			}

			$variant_key = $font['family'] . '-' . $font['weight'] . '-' . $font['style'];

			if ( ! isset( $resolved[ $variant_key ] ) ) {
				$resolved[ $variant_key ] = $font;
				continue;
			}

			// Prefer build/ over assets/
			$existing_path = $resolved[ $variant_key ]['relative_path'] ?? '';
			$new_path      = $font['relative_path'] ?? '';

			if (
				strpos( $new_path, 'build/' ) === 0 &&
				strpos( $existing_path, 'assets/' ) === 0
			) {
				$resolved[ $variant_key ] = $font;
			}
		}

		return array_values( $resolved );
	}
}


/**
 * Print fonts for debugging or summary.
 * Can display either per-file details or grouped family summary.
 *
 * @param array  $fonts   Array of font files or grouped font families.
 * @param bool   $grouped Whether to display as grouped families (true) or per-file (false).
 * @param string $label   Label for output.
 */
if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_print_font_summary' ) ) {
	function wdsbt_print_font_summary( $fonts, $grouped = false, $label = 'Fonts' ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output, escaping not required.
		printf( "%s\n", $label );

		if ( $grouped ) {
			foreach ( $fonts as $family ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
				printf(
					"- %s (%s): %d variants\n",
					$family['name'],
					$family['slug'],
					count( $family['fontFace'] ?? [] )
				);
			}
		} else {
			foreach ( $fonts as $font ) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
				printf(
					"  - %s | %s | Weight: %s | Style: %s\n",
					$font['relative_path'] ?? $font['path'],
					$font['extension'] ?? '',
					$font['weight'] ?? '',
					$font['style'] ?? ''
				);
			}
		}

		printf( "\n\n");

	}
}



/**
 * Parse font filename.
 *
 * @param string $filename Font filename to parse.
 * @return array Font metadata.
 */
if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_parse_font_meta_from_filename' ) ) {

	function wdsbt_parse_font_meta_from_filename( $filename ) {

		$metadata = array(
			'family' => 'Unknown',
			'weight' => '400',
			'style'  => 'normal',
		);

		$family_patterns = array(
			'inter'       => 'Inter',
			'oxygen'      => 'Oxygen',
			'roboto-mono' => 'Roboto Mono',
			'roboto'      => 'Roboto',
			'open-sans'   => 'Open Sans',
			'lato'        => 'Lato',
			'poppins'     => 'Poppins',
			'montserrat'  => 'Montserrat',
			'raleway'     => 'Raleway',
			'playfair'    => 'Playfair Display',
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

		// Create a list of keywords to ignore when parsing the family name in the case of unknown fonts
		// This includes all keys from the family, weight, and style patterns
		// Normalize by removing dashes and leading hyphens
		$all_keys = array_merge(
			array_keys($family_patterns),
			array_keys($weight_patterns),
			array_keys($style_patterns)
		);

		$ignore_keywords = array_map(function($key) {
			return ltrim(str_replace('-', '', $key), '-');
		}, $all_keys);

		// Remove duplicates and re-index the array
		$ignore_keywords = array_unique($ignore_keywords);
		$ignore_keywords = array_values($ignore_keywords);



		$lowercase_filename = strtolower( $filename );

		// Detect font family - use the longest matching pattern.
		$matched_family = '';
		$longest_match  = 0;

		foreach ( $family_patterns as $pattern => $family ) {
			if ( strpos( $lowercase_filename, $pattern ) !== false && strlen( $pattern ) > $longest_match ) {
				$matched_family = $family;
				$longest_match  = strlen( $pattern );
			}
		}

		if ( $matched_family ) {
			$metadata['family'] = $matched_family;
		}

		// If family name is not in the above list, try to extract from filename
		// This accounts for multi-part names and ignores common weight/style keywords
		if ( 'Unknown' === $metadata['family'] ) {
			// Split filename into parts (by -, _, or space)
			$parts = preg_split( '/[-_\s]+/', strtolower( $filename ) );

			if ( ! empty( $parts ) ) {
				// Remove ignored words
				$filtered = array_filter( $parts, function( $part ) use ( $ignore_keywords ) {
					return ! in_array( $part, $ignore_keywords, true );
				});

				if ( ! empty( $filtered ) ) {
					// Combine remaining parts and capitalize properly
					$metadata['family'] = ucwords( implode( ' ', $filtered ) );
				}
			}
		}

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

		return $metadata;
	}
}
