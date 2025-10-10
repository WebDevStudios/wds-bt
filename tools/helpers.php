#!/usr/bin/env php
<?php
/**
 * WDSBT Font Detection Tool.
 *
 * @package WDSBT
 */

// Prefix: wdsbt_.

/**
 * Map font family or path to a standardized slug.
 *
 * Accepts a family name string, a file path string, or a font array with 'path'/'family'.
 *
 * @param string|array $family_or_path Font family name, path, or font array.
 * @return string Standardized slug (e.g. 'body', 'headline', 'mono', or sanitized name).
 */

if ( ! function_exists( __NAMESPACE__ . '\\wdsbt_get_font_slug' ) ) {
	function wdsbt_get_font_slug( $family_or_path ) {
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

		foreach ( $family_patterns as $pattern => $family ) {
			if ( strpos( $lowercase_filename, $pattern ) !== false ) {
				$metadata['family'] = $family;
				break;
			}
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
