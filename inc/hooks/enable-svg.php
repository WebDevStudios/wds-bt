<?php
/**
 * Enable SVG uploads.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Add mime types to upload_mimes array.
 *
 * @param array $mimes  Array of mime types.
 * @return array
 */
function wdsbt_add_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\wdsbt_add_mime_types' );

/**
 * Checks the filetype.
 *
 * @param array  $data Array of file type data.
 * @param string $file Full path to the file.
 * @param string $filename The name of the file (may differ from $file due to $file being in a tmp directory).
 * @param array  $mimes Array of mime types keyed by their file extension regex, or null if none were provided.
 * @return array
 */
function wdsbt_check_filetype( $data, $file, $filename, $mimes ) {
	$filetype = wp_check_filetype( $filename, $mimes );

	return [
		'ext'             => $filetype['ext'],
		'type'            => $filetype['type'],
		'proper_filename' => $data['proper_filename'],
	];
}
add_filter( 'wp_check_filetype_and_ext', __NAMESPACE__ . '\wdsbt_check_filetype', 10, 4 );

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
