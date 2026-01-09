<?php
/**
 * WebP Uploads.
 *
 * Automatically generates WebP versions of uploaded JPEG and PNG images.
 * Inspired by WordPress Performance plugin: https://github.com/WordPress/performance/tree/trunk/plugins/webp-uploads.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Check if WebP generation is supported.
 *
 * @return bool True if WebP can be generated, false otherwise.
 */
function webp_supported() {
	// Check for Imagick with WebP support.
	if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
		$imagick = new \Imagick();
		$formats = $imagick->queryFormats();
		if ( in_array( 'WEBP', $formats, true ) ) {
			return true;
		}
	}

	// Check for GD with WebP support.
	if ( function_exists( 'imagewebp' ) ) {
		return true;
	}

	return false;
}

/**
 * Generate WebP version of an image.
 *
 * @param string $file_path Path to the original image file.
 * @param string $webp_path Path where the WebP file should be saved.
 * @return bool True on success, false on failure.
 */
function generate_webp( $file_path, $webp_path ) {
	if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
		return false;
	}

	$image_info = wp_getimagesize( $file_path );
	if ( ! $image_info ) {
		return false;
	}

	$mime_type = $image_info['mime'] ?? '';

	// Only process JPEG and PNG images.
	if ( ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return false;
	}

	// Try Imagick first (better quality).
	if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
		$imagick = new \Imagick();
		$formats = $imagick->queryFormats();
		if ( in_array( 'WEBP', $formats, true ) ) {
			try {
				$imagick->readImage( $file_path );
				$imagick->setImageFormat( 'WEBP' );
				$imagick->setImageCompressionQuality( 85 );
				$imagick->writeImage( $webp_path );
				$imagick->clear();
				$imagick->destroy();

				return file_exists( $webp_path );
			} catch ( \Exception $e ) {
				// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				error_log( 'Imagick WebP generation failed: ' . $e->getMessage() );
			}
		}
	}

	// Fallback to GD library.
	if ( function_exists( 'imagewebp' ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading local file path is safe.
		$image_data = file_get_contents( $file_path );
		if ( false === $image_data ) {
			return false;
		}

		$image = imagecreatefromstring( $image_data );
		if ( false === $image ) {
			return false;
		}

		// Create WebP directory if it doesn't exist.
		$webp_dir = dirname( $webp_path );
		if ( ! file_exists( $webp_dir ) ) {
			wp_mkdir_p( $webp_dir );
		}

		$result = imagewebp( $image, $webp_path, 85 );
		imagedestroy( $image );

		return $result && file_exists( $webp_path );
	}

	return false;
}

/**
 * Generate WebP version when an image is uploaded.
 *
 * @param int $attachment_id Attachment ID.
 */
function generate_webp_on_upload( $attachment_id ) {
	// Only process images.
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return;
	}

	// Check if WebP is supported.
	if ( ! webp_supported() ) {
		return;
	}

	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return;
	}

	// Generate WebP filename.
	$file_info = pathinfo( $file_path );
	$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

	// Generate WebP version.
	if ( generate_webp( $file_path, $webp_path ) ) {
		// Store WebP file path in attachment metadata.
		$upload_dir = wp_upload_dir();
		$webp_url   = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
		update_post_meta( $attachment_id, '_webp_file', $webp_path );
		update_post_meta( $attachment_id, '_webp_url', $webp_url );
	}
}
add_action( 'add_attachment', __NAMESPACE__ . '\\generate_webp_on_upload' );
add_action( 'edit_attachment', __NAMESPACE__ . '\\generate_webp_on_upload' );

/**
 * Generate WebP versions for all image sizes when attachment is updated.
 *
 * @param array $metadata    Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified metadata.
 */
function generate_webp_sizes( $metadata, $attachment_id ) {
	// Only process images.
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return $metadata;
	}

	// Check if WebP is supported.
	if ( ! webp_supported() ) {
		return $metadata;
	}

	if ( ! $metadata || empty( $metadata['sizes'] ) ) {
		return $metadata;
	}

	$upload_dir = wp_upload_dir();
	$file_dir   = dirname( get_attached_file( $attachment_id ) );

	// Generate WebP for each image size.
	foreach ( $metadata['sizes'] as $size_name => $size_data ) {
		if ( empty( $size_data['file'] ) ) {
			continue;
		}

		$size_file_path = $file_dir . '/' . $size_data['file'];
		if ( ! file_exists( $size_file_path ) ) {
			continue;
		}

		$file_info = pathinfo( $size_file_path );
		$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

		if ( generate_webp( $size_file_path, $webp_path ) ) {
			$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );

			// Store in metadata.
			if ( ! isset( $metadata['sizes'][ $size_name ]['webp'] ) ) {
				$metadata['sizes'][ $size_name ]['webp'] = array();
			}
			$metadata['sizes'][ $size_name ]['webp']['file'] = $file_info['filename'] . '.webp';
			$metadata['sizes'][ $size_name ]['webp']['url']  = $webp_url;
		}
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', __NAMESPACE__ . '\\generate_webp_sizes', 10, 2 );

/**
 * Get WebP URL for an attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return string|false WebP URL or false if not available.
 */
function get_webp_url( $attachment_id ) {
	return get_post_meta( $attachment_id, '_webp_url', true );
}

/**
 * Get WebP file path for an attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return string|false WebP file path or false if not available.
 */
function get_webp_file( $attachment_id ) {
	return get_post_meta( $attachment_id, '_webp_file', true );
}

/**
 * Check if browser supports WebP.
 *
 * @return bool True if browser supports WebP.
 */
function browser_supports_webp() {
	if ( ! isset( $_SERVER['HTTP_ACCEPT'] ) ) {
		return false;
	}

	$accept = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) );
	return strpos( $accept, 'image/webp' ) !== false;
}

/**
 * Add WebP source to image output when available.
 *
 * @param array $attr       Array of image attributes.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified attributes.
 */
function add_webp_source( $attr, $attachment_id ) {
	$webp_url = get_webp_url( $attachment_id );
	if ( ! $webp_url || ! browser_supports_webp() ) {
		return $attr;
	}

	// Add data attribute for JavaScript to use.
	$attr['data-webp'] = esc_url( $webp_url );

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\add_webp_source', 10, 2 );
