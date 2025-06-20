#!/usr/bin/env php
<?php
/**
 * WDSBT Font Detection Tool.
 *
 * @package WDSBT
 */

// Prefix: wdsbt_.

/**
 * Parse font filename.
 *
 * @param string $filename Font filename.
 * @return array Font metadata.
 */
function wdsbt_parse_font_filename( $filename ) {
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

	$lowercase_filename = strtolower( $filename );

	foreach ( $family_patterns as $pattern => $family ) {
		if ( strpos( $lowercase_filename, $pattern ) !== false ) {
			$metadata['family'] = $family;
			break;
		}
	}

	if ( 'Unknown' === $metadata['family'] ) {
		$parts = preg_split( '/[-_\s]+/', $filename );
		if ( ! empty( $parts[0] ) ) {
			$metadata['family'] = ucwords( str_replace( '-', ' ', $parts[0] ) );
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
			$font_metadata = wdsbt_parse_font_filename( $filename );
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
			"  - %s.%s | %s %s %s\n",
			$font['filename'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['extension'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['family'],    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['weight'],    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
			$font['style']      // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CLI output
		);
	}
}

// Example usage for CLI debugging.
$wdsbt_build_fonts  = wdsbt_scan_font_directory( 'build/fonts' );
$wdsbt_assets_fonts = wdsbt_scan_font_directory( 'assets/fonts' );
wdsbt_print_fonts( $wdsbt_build_fonts, 'Build Fonts' );
wdsbt_print_fonts( $wdsbt_assets_fonts, 'Assets Fonts' );
