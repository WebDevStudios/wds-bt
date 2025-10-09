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

if ( ! function_exists( __NAMESPACE__ . '\\get_font_slug' ) ) {
	function get_font_slug( $family_or_path ) {
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
				return sanitize_title( $segments[0] );
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

		return ! empty( $base ) ? sanitize_title( $base ) : 'unknown';
	}
}

/**
 * Sanitize a string into a URL/title-safe slug.
 *
 * @param string $text
 * @return string
 */
if ( ! function_exists( __NAMESPACE__ . '\\sanitize_title' ) ) {
	function sanitize_title( $text ) {
		$text = strtolower( trim( $text ) );
		$text = preg_replace( '/[^a-z0-9]+/', '-', $text );
		$text = trim( $text, '-' );
		return $text;
	}
}
