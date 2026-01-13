#!/usr/bin/env php
<?php
/**
 * WDSBT Font Detection Tool.
 *
 * @package WDSBT
 */

// Prefix: wdsbt_.

namespace WebDevStudios\wdsbt;

require_once __DIR__ . '/helpers.php';

/**
 * Scan directory for font files.
 *
 * @param string $directory Directory to scan.
 * @return array Array of font files.
 */
function wdsbt_scan_font_directory( $directory ) {

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
			$extension     = $file->getExtension();
			$filename      = $file->getBasename( '.' . $extension );
			$font_metadata = wdsbt_parse_font_meta_from_filename( $filename );
			$fonts[]       = array(
				'path'          => $file->getPathname(),
				'relative_path' => $relative_path,
				'filename'      => $filename,
				'extension'     => $extension,
				'family'        => $font_metadata['family'],
				'weight'        => $font_metadata['weight'],
				'style'         => $font_metadata['style'],
			);
		}
	}

	return $fonts;
}

/**
 * Print fonts for debugging.
 *
 * @param array  $fonts Array of font files.
 * @param string $label Label for output.
 */
function wdsbt_print_fonts( $fonts, $label = 'Fonts' ) {
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output, escaping not required.
	printf( "%s\n", $label );
	foreach ( $fonts as $font ) {
		printf(
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output, escaping not required.
			"  - %s.%s | %s | Weight: %s | Style: %s\n",
			$font['relative_path'],
			$font['filename'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['extension'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['family'],    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['weight'],    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['style']      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
		);
	}
}


// Example usage for CLI debugging.
$theme_dir = dirname( __DIR__, 1 );
$wdsbt_build_fonts  = wdsbt_scan_font_directory_raw( 'build/fonts', $theme_dir );
$wdsbt_assets_fonts = wdsbt_scan_font_directory_raw( 'assets/fonts', $theme_dir );

wdsbt_print_font_summary( $wdsbt_build_fonts, false, 'All Detected Build Fonts' );
wdsbt_print_font_summary( $wdsbt_assets_fonts, false, 'All Detected Assets Fonts' );
