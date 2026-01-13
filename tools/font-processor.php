#!/usr/bin/env php
<?php
/**
 * Font Processor Tool
 *
 * Processes existing font files to generate CSS, preload links,
 * and update theme.json entries.
 *
 * Usage: php tools/font-processor.php [options]
 *
 * @package WDSBT
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file.
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_copy -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_is_dir -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_is_file -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_unlink -- CLI tool
// phpcs:disable WordPress.WP.AlternativeFunctions.file_system_operations_rename -- CLI tool

namespace WebDevStudios\wdsbt;

require_once __DIR__ . '/helpers.php';

// Configuration.
$wdsbt_config = array(
	'input_dir'      => 'assets/fonts',
	'output_dir'     => 'build/fonts',
	'preload_output' => 'inc/setup/font-preload.php',
	'formats'        => array( 'woff2', 'woff' ),
);

/**
 * Parse command line arguments.
 */
function parse_args() {
	global $argv, $wdsbt_config;

	$options = array(
		'--input-dir'      => 'input_dir',
		'--output-dir'     => 'output_dir',
		'--preload-output' => 'preload_output',
		'--help'           => 'help',
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
	echo "Font Processor Tool\n\n";
	echo "Usage: php tools/font-processor.php [options]\n\n";
	echo "Options:\n";
	echo "  --input-dir DIR      Input directory (default: assets/fonts).\n";
	echo "  --output-dir DIR     Output directory (default: build/fonts).\n";
	echo "  --preload-output FILE Preload output file (default: inc/setup/font-preload.php).\n";
	echo "  --help               Show this help.\n\n";
	echo "Examples:\n";
	echo "  php tools/font-processor.php\n";
	echo "  php tools/font-processor.php --input-dir custom/fonts --output-dir dist/fonts\n";
}

/**
 * Scan for existing font files.
 *
 * @param string $input_dir Input directory to scan.
 * @return array Array of font files.
 */
function scan_font_files( $input_dir ) {
	$fonts     = array();
	$theme_dir = dirname( __DIR__, 1 );
	$full_path = $theme_dir . '/' . $input_dir;

	if ( ! is_dir( $full_path ) ) {
		echo 'Input directory not found: $full_path\n';
		return $fonts;
	}

	$iterator = new \RecursiveIteratorIterator(
		new \RecursiveDirectoryIterator( $full_path, \RecursiveDirectoryIterator::SKIP_DOTS )
	);

	// Keywords to ignore when detecting family name from filename.
	$ignore_keywords = [
		'100','200','300','400','500','600','700','800','900',
		'thin','extralight','light','regular','medium','semibold',
		'bold','extrabold','black','italic','oblique','normal'
	];

	foreach ( $iterator as $file ) {
		if ( $file->isFile() && in_array( strtolower( $file->getExtension() ), array( 'woff2', 'woff', 'ttf', 'otf' ), true ) ) {
			$relative_path = str_replace( $theme_dir . '/', '', $file->getPathname() );
			$filename      = $file->getBasename();

			// Detect font family from folder name (headline, body, mono).
			$folder_name   = basename( dirname( $file->getPathname() ) );
			$font_metadata = wdsbt_parse_font_meta_from_filename( $filename );

            // Smart fallback for Unknown family
            if ( 'Unknown' === $font_metadata['family'] ) {
                // Remove extension
                $base = preg_replace('/\.[^.]+$/','', $filename);
                // Split by - or _
                $parts = preg_split('/[-_]/', $base);
                // Keep parts that are not in ignore_keywords
                $clean_parts = array_filter($parts, function($part) use ($ignore_keywords) {
                    return ! in_array(strtolower($part), $ignore_keywords, true);
                });
                $font_metadata['family'] = ucwords( implode(' ', $clean_parts) );
            }

			$fonts[] = array(
				'path'          => $file->getPathname(),
				'relative_path' => $relative_path,
				'filename'      => $filename,
				'extension'     => $file->getExtension(),
				'family'        => $font_metadata['family'],
				'weight'        => $font_metadata['weight'],
				'style'         => $font_metadata['style'],
			);
		}
	}

	return $fonts;
}



/**
 * Copy fonts to output directory.
 *
 * @param array  $fonts      Array of font files.
 * @param string $output_dir Output directory.
 * @return array Array of copied fonts.
 */
function copy_fonts_to_output( $fonts, $output_dir ) {
	$copied_fonts    = array();
	$theme_dir       = dirname( __DIR__, 1 );
	$full_output_dir = $theme_dir . '/' . $output_dir;

	if ( ! is_dir( $full_output_dir ) ) {
		mkdir( $full_output_dir, 0755, true );
	}

	foreach ( $fonts as $font ) {
		//$standardized_slug = get_font_slug( $font['family'] );
		$standardized_slug = wdsbt_get_font_role_slug( $font['path'] );
		$family_dir        = $full_output_dir . '/' . $standardized_slug;

		if ( ! is_dir( $family_dir ) ) {
			mkdir( $family_dir, 0755, true );
		}

		$output_file = $family_dir . '/' . $font['filename'];

		if ( copy( $font['path'], $output_file ) ) {
			$copied_fonts[] = array(
				'family'    => $font['family'],
				'weight'    => $font['weight'],
				'style'     => $font['style'],
				'filename'  => $font['filename'],
				'extension' => $font['extension'],
				'path'      => str_replace( $theme_dir . '/', '', $output_file ),
			);

			printf(
				"  Copied %s to %s/\n",
				$font['filename'],
				$standardized_slug
			);
		} else {

			printf(
				"  Failed to copy %s\n",
				$font['filename']
			);
		}
	}

	return $copied_fonts;
}

/**
 * Generate font preload links.
 *
 * @param array  $fonts       Array of font files.
 * @param string $output_file Output file path.
 * @return bool Success status.
 */
function generate_font_preload( $fonts, $output_file ) {
	$theme_dir = dirname( __DIR__, 1 );
	$php_path  = $theme_dir . '/' . $output_file;

	// Create directory if it doesn't exist.
	$php_dir = dirname( $php_path );
	if ( ! is_dir( $php_dir ) ) {
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
	foreach ( $fonts as $font ) {
		$family = $font['family'];
		if ( ! isset( $preloaded[ $family ] ) ) {
			$preloaded[ $family ] = $font;
		}
	}

	foreach ( $preloaded as $font ) {
		$format            = 'woff2' === $font['extension'] ? 'font/woff2' : 'font/woff';
		$standardized_slug = wdsbt_get_font_role_slug( $font['family'] );
		$php              .= "    'fonts/{$standardized_slug}/{$font['filename']}' => '{$format}',\n";
	}

	$php .= "  ];\n\n";
	$php .= "  foreach ( \$preload_links as \$href => \$as ) {\n";
	$php .= "    echo '<link rel=\"preload\" href=\"' . esc_url( get_template_directory_uri() ) . '/build/' . esc_attr( \$href ) . '\" as=\"' . esc_attr( \$as ) . '\" crossorigin>';\n";
	$php .= "  }\n";
	$php .= "}\n";

	if ( file_put_contents( $php_path, $php ) ) {

		printf(
			"Generated font preload: %s\n",
			$output_file
		);
		return true;
	} else {

		printf(
			"Failed to generate font preload\n"
		);
		return false;
	}
}


/**
 * Main function.
 */
function main() {
	global $wdsbt_config;

	// Include the theme.json generator at the start of this function to ensure it's available.
	include_once __DIR__ . '/generate-theme-json.php';

	printf( "Font Processor Tool\n" );

	printf( "=====================\n\n" );

	// Parse arguments.
	parse_args();

	// Scan for existing fonts.
	printf( "\nScanning for existing fonts...\n" );
	$fonts = scan_font_files( $wdsbt_config['input_dir'] );

	if ( empty( $fonts ) ) {

		printf(
			"No fonts found in %s\n",
			$wdsbt_config['input_dir']
		);
		exit( 1 );
	}

	printf(
		"Found %d fonts:\n",
		count( $fonts )
	);
	foreach ( $fonts as $font ) {

		printf(
			"  - %s %s %s (%s)\n",
			$font['family'],
			$font['weight'],
			$font['style'],
			$font['extension']
		);
	}

	// Copy fonts to output directory.

	printf( "\nCopying fonts to output directory...\n" );
	$copied_fonts = copy_fonts_to_output( $fonts, $wdsbt_config['output_dir'] );

	if ( empty( $copied_fonts ) ) {

		printf( "No fonts were copied\n" );
		exit( 1 );
	}

	// Generate preload links.
	printf( "\nGenerating preload links...\n" );
	generate_font_preload( $copied_fonts, $wdsbt_config['preload_output'] );

	// Update theme.json.

	printf( "\nUpdating theme.json...\n" );
	generate_theme_json();

	printf( "\nFont processing complete!\n" );

	printf( "Generated files:\n" );

	printf(
		"  - Fonts: %s/\n",
		$wdsbt_config['output_dir']
	);

	printf(
		"  - Preload: %s\n",
		$wdsbt_config['preload_output']
	);

	printf( "  - Theme JSON: theme.json\n" );
}

// Run the processor.
main();

// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents -- CLI tool writing local file.
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_mkdir -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_copy -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_is_dir -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_is_file -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_unlink -- CLI tool
// phpcs:enable WordPress.WP.AlternativeFunctions.file_system_operations_rename -- CLI tool
