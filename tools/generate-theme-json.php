<?php
/**
 * Generate theme.json with detected fonts
 *
 * Usage: php tools/generate-theme-json.php
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

require_once __DIR__ . '/helpers.php';


/**
 * Generate theme.json with detected fonts.
 */
function generate_theme_json() {

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	printf( "\nScanning for fonts...\n" );

	// Get base theme.json content.
	$base_theme_json_path = dirname( __DIR__, 1 ) . '/theme.json';
	$base_theme_json      = array();

	if ( file_exists( $base_theme_json_path ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- CLI tool reading local file.
		$base_theme_json = json_decode( file_get_contents( $base_theme_json_path ), true );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "Loaded base theme.json\n" );
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "No base theme.json found, creating new one\n" );
	}

	// Get theme root directory.
	$theme_dir = dirname( __DIR__, 1 );

	// Scan for fonts in both build and assets directories.
	// $build_fonts  = scan_font_directory( 'build/fonts' );
	// $assets_fonts = scan_font_directory( 'assets/fonts' );

	// Scan for fonts in both build and assets directories.
	$build_fonts  = wdsbt_scan_font_directory_raw( 'build/fonts', $theme_dir );
	$assets_fonts = wdsbt_scan_font_directory_raw( 'assets/fonts', $theme_dir );

	// Merge fonts, preferring build fonts over assets fonts.
	$all_fonts = array_merge( $build_fonts, $assets_fonts );
	$unique_fonts   = wdsbt_resolve_fonts( $all_fonts );


	// Group fonts by family.
	$font_families = wdsbt_group_fonts_by_family( $unique_fonts );

	// Ensure typography settings exist.
	if ( ! isset( $base_theme_json['settings']['typography'] ) ) {
		$base_theme_json['settings']['typography'] = array();
	}

	// Update or add font families.
	if ( ! empty( $font_families ) ) {
		$base_theme_json['settings']['typography']['fontFamilies'] = array_values( $font_families );
	}

	// Count detected fonts.
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

	// Print a clean summary of detected font families.
	if ( $family_count > 0 ) {
		wdsbt_print_font_summary(
			$base_theme_json['settings']['typography']['fontFamilies'] ?? [],
			true,  // grouped = true to show one line per family
			'Detected Families'
		);
	}

	// Save the updated theme.json.
	$output_path = dirname( __DIR__, 1 ) . '/theme.json';

	// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode -- CLI tool
	$json_content = json_encode( $base_theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file.
	if ( file_put_contents( $output_path, $json_content ) ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "\n✅ Successfully generated theme.json with detected fonts\n" );
	} else {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( "\n❌ Failed to write theme.json\n" );
	}
}

// Run the generator.
generate_theme_json();
