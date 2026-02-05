#!/usr/bin/env php
<?php
/**
 * WDSBT Font Generator Tool.
 *
 * Generates optimized font files, CSS, and theme.json entries
 * from source font files.
 *
 * Usage: php tools/font-generator.php [options]
 *
 * @package WDSBT
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_copy -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_is_dir -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_is_file -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_unlink -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_rename -- CLI tool

namespace WebDevStudios\wdsbt;

// Configuration.
$wdsbt_config = array(
	'input_dir'      => 'assets/fonts',
	'output_dir'     => 'build/fonts',
	'css_output'     => 'assets/scss/base/_fonts.scss',
	'preload_output' => 'inc/setup/font-preload.php',
	'subset_text'    => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,!?@#$%^&*()_+-=[]{}|;:,.<>?/~`\'"\\',
	'formats'        => array( 'woff2', 'woff' ),
	'weights'        => array(
		'100' => 'Thin',
		'200' => 'ExtraLight',
		'300' => 'Light',
		'400' => 'Regular',
		'500' => 'Medium',
		'600' => 'SemiBold',
		'700' => 'Bold',
		'800' => 'ExtraBold',
		'900' => 'Black',
	),
);

/**
 * Parse command line arguments.
 */
function parse_args() {
	global $argv, $wdsbt_config;

	$options = array(
		'--input-dir'  => 'input_dir',
		'--output-dir' => 'output_dir',
		'--css-output' => 'css_output',
		'--subset'     => 'subset_text',
		'--formats'    => 'formats',
		'--weights'    => 'weights',
		'--help'       => 'help',
	);

	foreach ( $argv as $i => $arg ) {
		if ( isset( $options[ $arg ] ) ) {
			$key = $options[ $arg ];
			if ( 'help' === $key ) {
				show_help();
				exit( 0 );
			}
			if ( isset( $argv[ $i + 1 ] ) && ! str_starts_with( $argv[ $i + 1 ], '--' ) ) {
				$wdsbt_config[ $key ] = $argv[ $i + 1 ];
			}
		}
	}
}

/**
 * Show help information.
 */
function show_help() {
	echo "Font Generator Tool\n\n";
	echo "Usage: php tools/font-generator.php [options]\n\n";
	echo "Options:\n";
	echo "  --input-dir DIR     Input directory (default: assets/fonts)\n";
	echo "  --output-dir DIR    Output directory (default: build/fonts)\n";
	echo "  --css-output FILE   CSS output file (default: assets/scss/base/_fonts.scss)\n";
	echo "  --subset TEXT       Subset text for optimization\n";
	echo "  --formats LIST      Output formats (default: woff2,woff)\n";
	echo "  --weights LIST      Font weights to generate\n";
	echo "  --help              Show this help\n\n";
	echo "Examples:\n";
	echo "  php tools/font-generator.php\n";
	echo "  php tools/font-generator.php --input-dir custom/fonts --output-dir dist/fonts\n";
	echo "  php tools/font-generator.php --formats woff2 --subset 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'\n";
}

/**
 * Check if required tools are available.
 */
function check_dependencies() {
	$tools   = array( 'fonttools', 'woff2_compress' );
	$missing = array();

	foreach ( $tools as $tool ) {
		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec -- CLI tool
		$output = shell_exec( "which $tool 2>/dev/null" );
		if ( empty( $output ) ) {
			$missing[] = $tool;
		}
	}

	if ( ! empty( $missing ) ) {
		printf(
			'Missing required tools: %s
',
			implode( ', ', $missing )
		);
		printf( "Please install:\n" );
		printf( "  - fonttools: pip install fonttools\n" );
		printf( "  - woff2_compress: brew install woff2 (macOS) or apt-get install woff2 (Ubuntu)\n" );
		exit( 1 );
	}

	printf( "All required tools are available\n" );
}

/**
 * Scan for source font files.
 *
 * @param string $input_dir Input directory.
 * @return array Array of font files.
 */
function scan_source_fonts( $input_dir ) {
	$fonts     = array();
	$theme_dir = dirname( __DIR__, 1 );
	$full_path = $theme_dir . '/' . $input_dir;

	if ( ! is_dir( $full_path ) ) {
		printf(
			'Input directory not found: %s
',
			$full_path
		);
		return $fonts;
	}

	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $full_path, RecursiveDirectoryIterator::SKIP_DOTS )
	);

	foreach ( $iterator as $file ) {
		if ( $file->isFile() && in_array( strtolower( $file->getExtension() ), array( 'ttf', 'otf' ), true ) ) {
			$relative_path = str_replace( $theme_dir . '/', '', $file->getPathname() );
			$filename      = $file->getBasename();
			$font_metadata = parse_font_filename( $filename );

			$fonts[] = array(
				'path'          => $file->getPathname(),
				'relative_path' => $relative_path,
				'filename'      => $filename,
				'family'        => $font_metadata['family'],
				'weight'        => $font_metadata['weight'],
				'style'         => $font_metadata['style'],
			);
		}
	}

	return $fonts;
}

/**
 * Parse font filename (reuse from existing tool).
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

	// Remove file extension before parsing.
	$filename_without_ext = preg_replace( '/\.(woff2?|ttf|otf)$/i', '', $filename );
	$lowercase_filename   = strtolower( $filename_without_ext );

	// Detect font weight first - use exact matches.
	foreach ( $weight_patterns as $pattern => $weight ) {
		if ( strpos( $lowercase_filename, $pattern ) !== false ) {
			$metadata['weight'] = $weight;
			break;
		}
	}

	// Detect font style.
	foreach ( $style_patterns as $pattern => $style ) {
		if ( strpos( $lowercase_filename, $pattern ) !== false ) {
			$metadata['style'] = $style;
			break;
		}
	}

	// Extract font family from filename by removing weight/style patterns and metadata.
	$parts = preg_split( '/[-_\s]+/', $filename_without_ext );

	// Remove weight/style keywords and metadata from parts.
	$family_parts    = array();
	$weight_keywords = array( 'thin', 'extralight', 'light', 'regular', 'normal', 'medium', 'semibold', 'bold', 'extrabold', 'black', '100', '200', '300', '400', '500', '600', '700', '800', '900' );
	$style_keywords  = array( 'italic', 'oblique' );

	foreach ( $parts as $part ) {
		$lower_part = strtolower( $part );

		// Skip weight/style keywords.
		if ( in_array( $lower_part, $weight_keywords, true ) || in_array( $lower_part, $style_keywords, true ) ) {
			continue;
		}

		// Skip version numbers and metadata (v15, latin, etc.).
		if ( preg_match( '/^v\d+|^latin|^\d+$/', $lower_part ) ) {
			continue;
		}

		$family_parts[] = $part;
	}

	// Reconstruct family name from remaining parts.
	if ( ! empty( $family_parts ) ) {
		$metadata['family'] = ucwords( implode( ' ', $family_parts ) );
	} elseif ( ! empty( $parts[0] ) ) {
		$metadata['family'] = ucwords( str_replace( array( '-', '_' ), ' ', $parts[0] ) );
	}

	return $metadata;
}

/**
 * Generate font subsets and optimized formats.
 *
 * @param array  $source_fonts Array of source font files.
 * @param string $output_dir   Output directory.
 * @param array  $config       Configuration array.
 * @return array Array of generated fonts.
 */
function generate_font_files( $source_fonts, $output_dir, $config ) {
	$generated_fonts = array();
	$theme_dir       = dirname( __DIR__, 1 );
	$output_path     = $theme_dir . '/' . $output_dir;

	// Create output directory.
	if ( ! is_dir( $output_path ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
		mkdir( $output_path, 0755, true );
	}

	foreach ( $source_fonts as $font ) {
		printf( 'Processing %s %s %s...\n', $font['family'], $font['weight'], $font['style'] );

		$family_slug     = sanitize_title( $font['family'] );
		$output_filename = "{$family_slug}-{$font['weight']}-{$font['style']}";

		$generated_variants = array();

		foreach ( $config['formats'] as $format ) {
			$output_file = "{$output_path}/{$output_filename}.{$format}";

			// Generate subset and convert format.
			$success = generate_font_variant( $font['path'], $output_file, $format, $config['subset_text'] );

			if ( $success ) {
				$generated_variants[] = array(
					'path'     => str_replace( $theme_dir . '/', '', $output_file ),
					'format'   => $format,
					'filename' => basename( $output_file ),
				);
				printf(
					'  Generated %s
',
					$format
				);
			} else {
				printf(
					'  Failed to generate %s
',
					$format
				);
			}
		}

		if ( ! empty( $generated_variants ) ) {
			$generated_fonts[] = array(
				'family'   => $font['family'],
				'weight'   => $font['weight'],
				'style'    => $font['style'],
				'variants' => $generated_variants,
			);
		}
	}

	return $generated_fonts;
}

/**
 * Generate a single font variant.
 *
 * @param string $input_file   Input font file path.
 * @param string $output_file  Output font file path.
 * @param string $format       Output format.
 * @param string $subset_text  Subset text for optimization.
 * @return bool Success status.
 */
function generate_font_variant( $input_file, $output_file, $format, $subset_text ) {
	$temp_subset = tempnam( sys_get_temp_dir(), 'font_subset_' ) . '.ttf';

	// Create subset.
	$subset_cmd = "pyftsubset '{$input_file}' --text='{$subset_text}' --output-file='{$temp_subset}'";
	// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec -- CLI tool
	$subset_result = shell_exec( $subset_cmd . ' 2>&1' );

	if ( ! file_exists( $temp_subset ) ) {
		return false;
	}

	$success = false;

	switch ( $format ) {
		case 'woff2':
			$convert_cmd = "woff2_compress '{$temp_subset}'";
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec -- CLI tool
			$success = shell_exec( $convert_cmd . ' 2>&1' ) !== null;
			if ( $success ) {
				WP_Filesystem::move( $temp_subset . '.woff2', $output_file );
			}
			break;

		case 'woff':
			$convert_cmd = "pyftsubset '{$temp_subset}' --flavor=woff --output-file='{$output_file}'";
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec -- CLI tool
			$success = shell_exec( $convert_cmd . ' 2>&1' ) !== null;
			break;

		default:
			$success = copy( $temp_subset, $output_file );
			break;
	}

	// Clean up temp file.
	if ( file_exists( $temp_subset ) ) {
		wp_delete_file( $temp_subset );
	}

	return $success;
}

/**
 * Generate CSS for font loading.
 *
 * @param array  $generated_fonts Array of generated fonts.
 * @param string $output_file     Output CSS file path.
 * @return bool Success status.
 */
function generate_font_css( $generated_fonts, $output_file ) {
	$theme_dir = dirname( __DIR__, 1 );
	$css_path  = $theme_dir . '/' . $output_file;

	// Create directory if it doesn't exist.
	$css_dir = dirname( $css_path );
	if ( ! is_dir( $css_dir ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
		mkdir( $css_dir, 0755, true );
	}

	$css = "/* Auto-generated font CSS */\n\n";

	// Group fonts by family.
	$families = array();
	foreach ( $generated_fonts as $font ) {
		$family = $font['family'];
		if ( ! isset( $families[ $family ] ) ) {
			$families[ $family ] = array();
		}
		$families[ $family ][] = $font;
	}

	foreach ( $families as $family => $fonts ) {
		$css .= "/* {$family} */\n";

		foreach ( $fonts as $font ) {
			$css .= "@font-face {\n";
			$css .= "  font-family: '{$family}';\n";
			$css .= "  font-style: {$font['style']};\n";
			$css .= "  font-weight: {$font['weight']};\n";
			$css .= "  font-display: swap;\n";
			$css .= '  src: ';

			$src_parts = array();
			foreach ( $font['variants'] as $variant ) {
				$format      = 'woff2' === $variant['format'] ? 'woff2' : 'woff';
				$src_parts[] = "url('../fonts/{$variant['filename']}') format('{$format}')";
			}

			$css .= implode( ', ', $src_parts ) . ";\n";
			$css .= "}\n\n";
		}
	}

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool
	if ( file_put_contents( $css_path, $css ) ) {
		printf(
			'Generated font CSS: %s
',
			$output_file
		);
		return true;
	} else {
		printf(
			'Failed to generate font CSS
'
		);
		return false;
	}
}

/**
 * Generate font preload links.
 *
 * @param array  $generated_fonts Array of generated fonts.
 * @param string $output_file     Output PHP file path.
 * @return bool Success status.
 */
function generate_font_preload( $generated_fonts, $output_file ) {
	$theme_dir = dirname( __DIR__, 1 );
	$php_path  = $theme_dir . '/' . $output_file;

	// Create directory if it doesn't exist.
	$php_dir = dirname( $php_path );
	if ( ! is_dir( $php_dir ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
		mkdir( $php_dir, 0755, true );
	}

	$php  = "<?php\n";
	$php .= "/**\n";
	$php .= " * Auto-generated font preload links.\n";
	$php .= " */\n\n";
	$php .= "function wdsbt_font_preload_links() {\n";
	$php .= "  \$preload_links = [\n";

	// Get the first variant of each font family for preloading.
	$preloaded = array();
	foreach ( $generated_fonts as $font ) {
		$family = $font['family'];
		if ( ! isset( $preloaded[ $family ] ) ) {
			$preloaded[ $family ] = $font;
		}
	}

	foreach ( $preloaded as $font ) {
		$first_variant = $font['variants'][0];
		$php          .= "    'fonts/{$first_variant['filename']}' => 'font/{$first_variant['format']}',\n";
	}

	$php .= "  ];\n\n";
	$php .= "  foreach (\$preload_links as \$href => \$as) {\n";
	$php .= "    echo '<link rel=\"preload\" href=\"' . get_template_directory_uri() . '/{$output_dir}/' . \$href . '\" as=\"' . \$as . '\" crossorigin>';\n";
	$php .= "  }\n";
	$php .= "}\n";

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool
	if ( file_put_contents( $php_path, $php ) ) {
		printf(
			'Generated font preload: %s
',
			$output_file
		);
		return true;
	} else {
		printf(
			'Failed to generate font preload
'
		);
		return false;
	}
}

/**
 * Sanitize title (reuse from existing tool).
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
 * Main function.
 */
function main() {
	global $wdsbt_config;

	printf( "Font Generator Tool\n" );

	printf( "=====================\n\n" );

	// Parse arguments.
	parse_args();

	// Check dependencies.
	check_dependencies();

	// Scan for source fonts.
	printf( "\nScanning for source fonts...\n" );
	$source_fonts = scan_source_fonts( $wdsbt_config['input_dir'] );

	if ( empty( $source_fonts ) ) {

		printf(
			"No source fonts found in %s\n",
			$wdsbt_config['input_dir']
		);
		exit( 1 );
	}

	printf(
		"Found %d source fonts:\n",
		count( $source_fonts )
	);
	foreach ( $source_fonts as $font ) {

		printf(
			"  - %s %s %s\n",
			$font['family'],
			$font['weight'],
			$font['style']
		);
	}

	// Generate optimized fonts.

	printf( "\nGenerating optimized fonts...\n" );
	$generated_fonts = generate_font_files( $source_fonts, $wdsbt_config['output_dir'], $wdsbt_config );

	if ( empty( $generated_fonts ) ) {

		printf( "No fonts were generated\n" );
		exit( 1 );
	}

	printf( "\nGenerated %d font families\n", count( $generated_fonts ) );

	// Generate CSS.
	printf( "\nGenerating CSS...\n" );
	generate_font_css( $generated_fonts, $wdsbt_config['css_output'] );

	// Generate preload links.
	printf( "\nGenerating preload links...\n" );
	generate_font_preload( $generated_fonts, $wdsbt_config['preload_output'] );

	// Update theme.json.
	printf( "\nUpdating theme.json...\n" );
	include_once __DIR__ . '/generate-theme-json.php';
	generate_theme_json();

	printf( "\nFont generation complete!\n" );

	printf( "Generated files:\n" );

	printf(
		"  - Fonts: %s/\n",
		$wdsbt_config['output_dir']
	);

	printf(
		"  - CSS: %s\n",
		$wdsbt_config['css_output']
	);

	printf(
		"  - Preload: %s\n",
		$wdsbt_config['preload_output']
	);

	printf( "  - Theme JSON: theme.json\n" );
}

// Run the generator.
main();

// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_copy -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_is_dir -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_is_file -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_unlink -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_rename -- CLI tool
