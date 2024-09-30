<?php
/**
 * Modifies the custom logo output to add a class to the SVG and remove unwanted attributes.
 *
 * This function filters the `get_custom_logo()` output and, if the logo is an SVG,
 * outputs the full SVG markup with a custom class added to the <svg> tag and removes
 * unwanted attributes such as mask-type="luminance".
 *
 * Additionally, this file includes functionality to allow SVG uploads in WordPress.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enable SVG uploads in WordPress.
 *
 * This function allows users to upload SVG files by adding the necessary MIME type
 * to the allowed file types. Use this cautiously, as SVGs can contain malicious code.
 *
 * @param array $mimes Existing array of allowed mime types.
 * @return array Updated array with SVG support.
 */
function enable_svg_upload( $mimes ) {
	// Add SVG MIME type to allowed file types.
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\enable_svg_upload' );

/**
 * Filter the custom logo output to add class to SVG logo and remove unwanted attributes.
 *
 * This function retrieves the custom logo, checks if it's an SVG, adds a custom class to the
 * <svg> tag, removes the `mask-type="luminance"` attribute, and replaces the <img> tag
 * in the original logo output with the full SVG markup.
 *
 * @param string $html The HTML output of the custom logo.
 * @return string Filtered HTML output with full SVG markup and a custom class.
 */
function custom_logo_output( $html ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$logo_mime_type = get_post_mime_type( $custom_logo_id );

	// Check if the logo is an SVG.
	if ( 'image/svg+xml' === $logo_mime_type ) {
		$logo_url = wp_get_attachment_url( $custom_logo_id );

		// Use wp_remote_get() to fetch the SVG content.
		$response = wp_remote_get( $logo_url );

		// Check for successful response and body content.
		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			$svg = wp_remote_retrieve_body( $response );

			if ( $svg ) {
				// Add a class to the SVG by replacing the opening <svg> tag with <svg class="custom-logo-svg">.
				$svg = preg_replace( '/<svg/', '<svg class="custom-logo-svg"', $svg );

				// Remove mask-type="luminance" from the SVG.
				$svg = preg_replace( '/mask-type="luminance"/', '', $svg );

				// Replace the <img> tag in the original logo output with the modified SVG.
				$html = preg_replace( '/<img[^>]+>/', $svg, $html );
			}
		}
	}

	return $html;
}
add_filter( 'get_custom_logo', __NAMESPACE__ . '\custom_logo_output' );
