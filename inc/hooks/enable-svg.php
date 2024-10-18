<?php
/**
 * Enable SVG uploads.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

// Include Composer autoloader
$autoloader_path = dirname( __FILE__, 3 ) . '/vendor/autoload.php';
if ( file_exists( $autoloader_path ) ) {
	require_once $autoloader_path;
} else {
	error_log( 'Composer autoloader not found. Path tried: ' . $autoloader_path );
}

use enshrined\svgSanitize\Sanitizer;

/**
 * Add mime types to upload_mimes array.
 *
 * @param array $mimes Array of mime types.
 * @return array
 */
function wdsbt_add_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\wdsbt_add_mime_types' );

/**
 * Sanitize SVG files during upload.
 *
 * @param array $file An array of data for a single file.
 * @return array
 */
function wdsbt_sanitize_svg_upload( $file ) {
	if ( $file['type'] === 'image/svg+xml' ) {
		$sanitizer = new Sanitizer();
		$dirty_svg = file_get_contents( $file['tmp_name'] );
		$clean_svg = $sanitizer->sanitize( $dirty_svg );
		
		if ( $clean_svg === false ) {
			$file['error'] = 'SVG sanitization failed';
		} else {
			file_put_contents( $file['tmp_name'], $clean_svg );
		}
	}
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', __NAMESPACE__ . '\wdsbt_sanitize_svg_upload' );

/**
 * Fix for displaying svg files in media library.
 *
 * @return void
 */
function wdsbt_fix_svg() {
	echo '<style type="text/css">
			.attachment-266x266, .thumbnail img {
			 width: 100% !important;
			 height: auto !important;
			}
			</style>';
}
add_action( 'admin_head', __NAMESPACE__ . '\wdsbt_fix_svg' );
