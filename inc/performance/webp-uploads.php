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
 * Check if a URL belongs to the local WordPress site.
 *
 * @param string $url The URL to check.
 * @return bool True if the URL is from the local site.
 */
function is_local_url( $url ) {
	if ( empty( $url ) ) {
		return false;
	}

	$site_url = site_url();
	$home_url = home_url();

	$parsed_url = wp_parse_url( $url );
	if ( ! $parsed_url || empty( $parsed_url['host'] ) ) {
		return true;
	}

	$parsed_site = wp_parse_url( $site_url );
	$parsed_home = wp_parse_url( $home_url );

	$site_host = $parsed_site['host'] ?? '';
	$home_host = $parsed_home['host'] ?? '';

	return ( $parsed_url['host'] === $site_host || $parsed_url['host'] === $home_host );
}

/**
 * Check if WebP generation is supported.
 *
 * @return bool True if WebP can be generated, false otherwise.
 */
function webp_supported() {
	if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
		try {
			$imagick = new \Imagick();
			$formats = $imagick->queryFormats();
			if ( is_array( $formats ) && in_array( 'WEBP', $formats, true ) ) {
				return true;
			}
		} catch ( \Exception $e ) {
			// Imagick not available, continue to GD check.
			unset( $e );
		} catch ( \Error $e ) {
			// Imagick not available, continue to GD check.
			unset( $e );
		}
	}

	if ( extension_loaded( 'gd' ) ) {
		if ( function_exists( 'imagewebp' ) ) {
			return true;
		}
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

	if ( ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return false;
	}

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
				// Imagick failed, continue to GD fallback.
				unset( $e );
			}
		}
	}

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
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return;
	}

	if ( ! webp_supported() ) {
		return;
	}

	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return;
	}

	$file_info = pathinfo( $file_path );
	$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

	if ( generate_webp( $file_path, $webp_path ) ) {
		$upload_dir = wp_upload_dir();
		$webp_url   = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
		update_post_meta( $attachment_id, '_webp_file', $webp_path );
		update_post_meta( $attachment_id, '_webp_url', $webp_url );
	}
}
add_action( 'add_attachment', __NAMESPACE__ . '\\generate_webp_on_upload' );
add_action( 'edit_attachment', __NAMESPACE__ . '\\generate_webp_on_upload' );

/**
 * Regenerate WebP versions for an existing attachment.
 *
 * @param int $attachment_id Attachment ID.
 * @return bool True on success, false on failure.
 */
function regenerate_webp_for_attachment( $attachment_id ) {
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return false;
	}

	if ( ! webp_supported() ) {
		return false;
	}

	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		return false;
	}

	$file_info = pathinfo( $file_path );
	$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

	if ( generate_webp( $file_path, $webp_path ) ) {
		$upload_dir = wp_upload_dir();
		$webp_url   = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
		update_post_meta( $attachment_id, '_webp_file', $webp_path );
		update_post_meta( $attachment_id, '_webp_url', $webp_url );
	}

	$metadata = wp_get_attachment_metadata( $attachment_id );
	if ( $metadata && ! empty( $metadata['sizes'] ) ) {
		$metadata = generate_webp_sizes( $metadata, $attachment_id );
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}

	return true;
}

/**
 * Regenerate WebP when attachment metadata is updated (e.g., during thumbnail regeneration).
 *
 * @param array $metadata    Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified metadata.
 */
function regenerate_webp_on_metadata_update( $metadata, $attachment_id ) {
	if ( ! $metadata || empty( $metadata['sizes'] ) ) {
		return $metadata;
	}

	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return $metadata;
	}

	if ( ! webp_supported() ) {
		return $metadata;
	}

	$file_path = get_attached_file( $attachment_id );
	if ( $file_path && file_exists( $file_path ) ) {
		$file_info = pathinfo( $file_path );
		$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

		if ( generate_webp( $file_path, $webp_path ) ) {
			$upload_dir = wp_upload_dir();
			$webp_url   = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
			update_post_meta( $attachment_id, '_webp_file', $webp_path );
			update_post_meta( $attachment_id, '_webp_url', $webp_url );
		}
	}

	return $metadata;
}
add_filter( 'wp_update_attachment_metadata', __NAMESPACE__ . '\\regenerate_webp_on_metadata_update', 10, 2 );

/**
 * Generate WebP versions for all image sizes when attachment is updated.
 *
 * @param array $metadata    Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified metadata.
 */
function generate_webp_sizes( $metadata, $attachment_id ) {
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return $metadata;
	}

	if ( ! webp_supported() ) {
		return $metadata;
	}

	$upload_dir = wp_upload_dir();
	$file_path  = get_attached_file( $attachment_id );

	if ( $file_path && file_exists( $file_path ) ) {
		$file_info = pathinfo( $file_path );
		$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

		if ( ! file_exists( $webp_path ) && generate_webp( $file_path, $webp_path ) ) {
			$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
			update_post_meta( $attachment_id, '_webp_file', $webp_path );
			update_post_meta( $attachment_id, '_webp_url', $webp_url );
		}
	}

	if ( ! $metadata || empty( $metadata['sizes'] ) ) {
		return $metadata;
	}

	$file_dir = dirname( $file_path );

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
	if ( isset( $_SERVER['HTTP_ACCEPT'] ) ) {
		$accept = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) );
		if ( strpos( $accept, 'image/webp' ) !== false ) {
			return true;
		}
		return true;
	}

	return true;
}

/**
 * Add WebP source to image output when available.
 *
 * @param array $attr       Array of image attributes.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified attributes.
 */
function add_webp_source( $attr, $attachment_id ) {
	if ( ! browser_supports_webp() || ! webp_supported() ) {
		return $attr;
	}

	if ( ! isset( $attr['src'] ) ) {
		return $attr;
	}

	$src_url  = $attr['src'];
	$webp_url = null;

	// Get attachment metadata.
	$metadata = wp_get_attachment_metadata( $attachment_id );
	if ( ! $metadata ) {
		return $attr;
	}

	$upload_dir = wp_upload_dir();
	$file_dir   = dirname( get_attached_file( $attachment_id ) );

	$src_path     = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $src_url );
	$src_filename = basename( $src_path );

	$file_path     = get_attached_file( $attachment_id );
	$full_filename = basename( $file_path );
	if ( $src_filename === $full_filename ) {
		$webp_url = get_webp_url( $attachment_id );
		if ( ! $webp_url && file_exists( $file_path ) ) {
			$file_info = pathinfo( $file_path );
			$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
			if ( ! file_exists( $webp_path ) ) {
				generate_webp( $file_path, $webp_path );
			}
			if ( file_exists( $webp_path ) ) {
				$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
			}
		}
	} else {
		$matching_size = null;
		foreach ( $metadata['sizes'] as $size_name => $size_data ) {
			if ( ! empty( $size_data['file'] ) && $size_data['file'] === $src_filename ) {
				$matching_size = $size_data;
				break;
			}
		}

		if ( $matching_size ) {
			if ( ! empty( $matching_size['webp']['url'] ) ) {
				$webp_url = $matching_size['webp']['url'];
			} elseif ( ! empty( $matching_size['file'] ) ) {
				$size_file = $file_dir . '/' . $matching_size['file'];
				if ( file_exists( $size_file ) ) {
					$file_info = pathinfo( $size_file );
					$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

					if ( ! file_exists( $webp_path ) ) {
						generate_webp( $size_file, $webp_path );
					}

					if ( file_exists( $webp_path ) ) {
						$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
					}
				}
			}
		} elseif ( file_exists( $src_path ) ) {
				$file_info = pathinfo( $src_path );
				$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

			if ( ! file_exists( $webp_path ) ) {
				generate_webp( $src_path, $webp_path );
			}

			if ( file_exists( $webp_path ) ) {
				$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
			}
		}
	}

	if ( $webp_url ) {
		$attr['src'] = esc_url( $webp_url );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\add_webp_source', 10, 2 );

/**
 * Replace image URL at the source level - intercept wp_get_attachment_image_src.
 *
 * @param string|false $image         Either array with src, width & height, icon src, or false.
 * @param int          $attachment_id Image attachment ID.
 * @param string|array $size          Size of image. Image size or array of width and height values.
 * @param bool         $icon          Whether the image should be treated as an icon.
 * @return array|false Modified image data or false.
 */
function replace_attachment_image_src_with_webp( $image, $attachment_id, $size, $icon ) {
	if ( ! $image || ! is_array( $image ) || $icon || ! browser_supports_webp() || ! webp_supported() ) {
		return $image;
	}

	$upload_dir = wp_upload_dir();
	$src_url    = $image[0];

	$webp_url = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $src_url );

	$parsed_url = wp_parse_url( $webp_url );
	if ( isset( $parsed_url['path'] ) ) {
		$uploads_parsed = wp_parse_url( $upload_dir['baseurl'] );
		$uploads_path   = isset( $uploads_parsed['path'] ) ? $uploads_parsed['path'] : '';
		if ( $uploads_path && strpos( $parsed_url['path'], $uploads_path ) === 0 ) {
			$relative_path = substr( $parsed_url['path'], strlen( $uploads_path ) );
			$webp_path     = $upload_dir['basedir'] . $relative_path;

			if ( file_exists( $webp_path ) ) {
				$image[0] = $webp_url;
				return $image;
			}
		}
	}

	return $image;
}
add_filter( 'wp_get_attachment_image_src', __NAMESPACE__ . '\\replace_attachment_image_src_with_webp', 10, 4 );

/**
 * Replace srcset URLs with WebP versions when available.
 *
 * @param array  $sources    Array of source data.
 * @param array  $size_array Array of width and height values.
 * @param string $image_src  The 'src' of the image.
 * @param array  $image_meta The image metadata as returned by 'wp_get_attachment_metadata()'.
 * @param int    $attachment_id Image attachment ID.
 * @return array Modified sources.
 */
function replace_srcset_with_webp( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	if ( ! browser_supports_webp() || ! webp_supported() ) {
		return $sources;
	}

	// Get attachment metadata to find WebP versions of sizes.
	$metadata = wp_get_attachment_metadata( $attachment_id );
	if ( ! $metadata ) {
		return $sources;
	}

	$upload_dir = wp_upload_dir();
	$file_dir   = dirname( get_attached_file( $attachment_id ) );
	$file_path  = get_attached_file( $attachment_id );

	foreach ( $sources as $width => $source ) {
		if ( ! isset( $source['url'] ) ) {
			continue;
		}

		$webp_url   = null;
		$source_url = $source['url'];

		if ( isset( $image_meta['width'] ) && (int) $image_meta['width'] === (int) $width ) {
			$webp_url = get_webp_url( $attachment_id );
			if ( ! $webp_url && file_exists( $file_path ) ) {
				$file_info = pathinfo( $file_path );
				$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
				if ( ! file_exists( $webp_path ) ) {
					generate_webp( $file_path, $webp_path );
				}
				if ( file_exists( $webp_path ) ) {
					$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
				}
			}
		} else {
			$matching_size = null;
			foreach ( $metadata['sizes'] as $size_name => $size_data ) {
				if ( isset( $size_data['width'] ) && (int) $size_data['width'] === (int) $width ) {
					$matching_size = $size_data;
					break;
				}
			}

			if ( ! $matching_size ) {
				$source_path     = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $source_url );
				$source_filename = basename( $source_path );
				foreach ( $metadata['sizes'] as $size_name => $size_data ) {
					if ( ! empty( $size_data['file'] ) && $size_data['file'] === $source_filename ) {
						$matching_size = $size_data;
						break;
					}
				}
			}

			if ( $matching_size ) {
				if ( ! empty( $matching_size['webp']['url'] ) ) {
					$webp_url = $matching_size['webp']['url'];
				} elseif ( ! empty( $matching_size['file'] ) ) {
					$size_file = $file_dir . '/' . $matching_size['file'];
					if ( file_exists( $size_file ) ) {
						$file_info = pathinfo( $size_file );
						$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

						if ( ! file_exists( $webp_path ) ) {
							generate_webp( $size_file, $webp_path );
						}

						if ( file_exists( $webp_path ) ) {
							$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
						}
					}
				}
			} else {
				$source_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $source_url );
				if ( file_exists( $source_path ) ) {
					$file_info = pathinfo( $source_path );
					$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

					if ( ! file_exists( $webp_path ) ) {
						generate_webp( $source_path, $webp_path );
					}

					if ( file_exists( $webp_path ) ) {
						$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
					}
				}
			}
		}

		if ( $webp_url ) {
			$sources[ $width ]['url'] = esc_url( $webp_url );
		}
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', __NAMESPACE__ . '\\replace_srcset_with_webp', 10, 5 );

/**
 * Replace image URLs with WebP versions in content using wp_content_img_tag filter.
 * This handles all image tags in content, including those rendered by blocks.
 *
 * @param string $filtered_image The filtered image HTML.
 * @param string $context        Additional context about how the function was called.
 * @param int    $attachment_id  Image attachment ID.
 * @return string Modified image HTML.
 */
function replace_content_image_with_webp( $filtered_image, $context, $attachment_id ) {
	if ( ! browser_supports_webp() || ! webp_supported() ) {
		return $filtered_image;
	}

	if ( ! $attachment_id ) {
		return $filtered_image;
	}

	$upload_dir = wp_upload_dir();
	$metadata   = wp_get_attachment_metadata( $attachment_id );
	if ( ! $metadata ) {
		return $filtered_image;
	}

	$file_dir = dirname( get_attached_file( $attachment_id ) );

	$filtered_image = preg_replace_callback(
		'/<img([^>]+)src=["\']([^"\']+)["\']([^>]*)>/i',
		function ( $matches ) use ( $upload_dir, $metadata, $file_dir, $attachment_id ) {
			$before_src = $matches[1];
			$src_url    = $matches[2];
			$after_src  = $matches[3];

			$webp_url     = null;
			$src_path     = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $src_url );
			$src_filename = basename( $src_path );

			$file_path     = get_attached_file( $attachment_id );
			$full_filename = basename( $file_path );
			if ( $src_filename === $full_filename ) {
				$webp_url = get_webp_url( $attachment_id );
				if ( ! $webp_url && file_exists( $file_path ) ) {
					$file_info = pathinfo( $file_path );
					$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
					if ( ! file_exists( $webp_path ) ) {
						generate_webp( $file_path, $webp_path );
					}
					if ( file_exists( $webp_path ) ) {
						$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
					}
				}
			} else {
				$matching_size = null;
				foreach ( $metadata['sizes'] as $size_name => $size_data ) {
					if ( ! empty( $size_data['file'] ) && $size_data['file'] === $src_filename ) {
						$matching_size = $size_data;
						break;
					}
				}

				if ( $matching_size ) {
					if ( ! empty( $matching_size['webp']['url'] ) ) {
						$webp_url = $matching_size['webp']['url'];
					} elseif ( ! empty( $matching_size['file'] ) ) {
						$size_file = $file_dir . '/' . $matching_size['file'];
						if ( file_exists( $size_file ) ) {
							$file_info = pathinfo( $size_file );
							$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

							if ( ! file_exists( $webp_path ) ) {
								generate_webp( $size_file, $webp_path );
							}

							if ( file_exists( $webp_path ) ) {
								$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
							}
						}
					}
				} elseif ( file_exists( $src_path ) ) {
						$file_info = pathinfo( $src_path );
						$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

					if ( ! file_exists( $webp_path ) ) {
						generate_webp( $src_path, $webp_path );
					}

					if ( file_exists( $webp_path ) ) {
						$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
					}
				}
			}

			if ( $webp_url ) {
				return '<img' . $before_src . 'src="' . esc_url( $webp_url ) . '"' . $after_src . '>';
			}

			return $matches[0];
		},
		$filtered_image
	);

	$filtered_image = preg_replace_callback(
		'/<img([^>]+)srcset=["\']([^"\']+)["\']([^>]*)>/i',
		function ( $matches ) use ( $upload_dir, $metadata, $file_dir, $attachment_id ) {
			$before_srcset = $matches[1];
			$srcset_value  = $matches[2];
			$after_srcset  = $matches[3];

			$srcset_parts = explode( ',', $srcset_value );
			$new_srcset   = array();

			foreach ( $srcset_parts as $part ) {
				$part = trim( $part );
				if ( empty( $part ) ) {
					continue;
				}

				if ( preg_match( '/^(.+?)\s+(\d+)w$/', $part, $url_matches ) ) {
					$url   = trim( $url_matches[1] );
					$width = (int) $url_matches[2];

					$webp_url     = null;
					$src_path     = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
					$src_filename = basename( $src_path );

					$matching_size = null;
					foreach ( $metadata['sizes'] as $size_name => $size_data ) {
						if ( isset( $size_data['width'] ) && (int) $size_data['width'] === (int) $width ) {
							$matching_size = $size_data;
							break;
						}
					}

					if ( ! $matching_size ) {
						foreach ( $metadata['sizes'] as $size_name => $size_data ) {
							if ( ! empty( $size_data['file'] ) && $size_data['file'] === $src_filename ) {
								$matching_size = $size_data;
								break;
							}
						}
					}

					if ( $matching_size ) {
						if ( ! empty( $matching_size['webp']['url'] ) ) {
							$webp_url = $matching_size['webp']['url'];
						} elseif ( ! empty( $matching_size['file'] ) ) {
							$size_file = $file_dir . '/' . $matching_size['file'];
							if ( file_exists( $size_file ) ) {
								$file_info = pathinfo( $size_file );
								$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

								if ( ! file_exists( $webp_path ) ) {
									generate_webp( $size_file, $webp_path );
								}

								if ( file_exists( $webp_path ) ) {
									$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
								}
							}
						}
					} elseif ( file_exists( $src_path ) ) {
							$file_info = pathinfo( $src_path );
							$webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';

						if ( ! file_exists( $webp_path ) ) {
							generate_webp( $src_path, $webp_path );
						}

						if ( file_exists( $webp_path ) ) {
							$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
						}
					}

					if ( $webp_url ) {
						$new_srcset[] = esc_url( $webp_url ) . ' ' . $width . 'w';
					} else {
						$new_srcset[] = $part;
					}
				} else {
					$new_srcset[] = $part;
				}
			}

			if ( ! empty( $new_srcset ) ) {
				return '<img' . $before_srcset . 'srcset="' . esc_attr( implode( ', ', $new_srcset ) ) . '"' . $after_srcset . '>';
			}

			return $matches[0];
		},
		$filtered_image
	);

	return $filtered_image;
}
add_filter( 'wp_content_img_tag', __NAMESPACE__ . '\\replace_content_image_with_webp', 10, 3 );

/**
 * Replace image URLs with WebP versions in block-rendered content.
 * This ensures block-rendered images are converted to WebP.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content.
 */
function replace_block_image_with_webp( $block_content, $block ) {
	$block_name = $block['blockName'] ?? '';

	if ( ! webp_supported() ) {
		return $block_content;
	}

	if ( ! in_array( $block_name, array( 'core/image', 'core/cover' ), true ) ) {
		return $block_content;
	}

	$block_content = preg_replace_callback(
		'/(<img[^>]+src=["\'])([^"\']+\.)(jpg|jpeg|png)(["\'][^>]*>)/i',
		function ( $matches ) {
			$url = $matches[2] . $matches[3];
			if ( ! is_local_url( $url ) ) {
				return $matches[0];
			}
			return $matches[1] . $matches[2] . 'webp' . $matches[4];
		},
		$block_content
	);

	$block_content = preg_replace_callback(
		'/(<img[^>]+srcset=["\'])([^"\']+)(["\'][^>]*>)/i',
		function ( $matches ) {
			$before = $matches[1];
			$srcset = $matches[2];
			$after  = $matches[3];

			$srcset_parts = explode( ',', $srcset );
			$new_parts    = array();

			foreach ( $srcset_parts as $part ) {
				$part = trim( $part );
				if ( empty( $part ) ) {
					continue;
				}

				if ( preg_match( '/^(.+?\.(?:jpg|jpeg|png))(?:\s+(\d+)w)?$/i', $part, $url_matches ) ) {
					$url = trim( $url_matches[1] );
					if ( is_local_url( $url ) ) {
						$width       = isset( $url_matches[2] ) ? ' ' . $url_matches[2] . 'w' : '';
						$new_parts[] = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $url ) . $width;
					} else {
						$new_parts[] = $part;
					}
				} else {
					$new_parts[] = $part;
				}
			}

			return $before . implode( ', ', $new_parts ) . $after;
		},
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\\replace_block_image_with_webp', 20, 2 );

/**
 * Replace image URLs with WebP in final content output.
 * This is a catch-all filter that processes all image tags in the content.
 *
 * @param string $content The post content.
 * @return string Modified content.
 */
function replace_images_in_content_with_webp( $content ) {
	if ( ! browser_supports_webp() || ! webp_supported() ) {
		return $content;
	}

	$upload_dir = wp_upload_dir();
	$site_url   = site_url();

	$content = preg_replace_callback(
		'/<img([^>]+)src=["\']([^"\']+\.(?:jpg|jpeg|png))["\']([^>]*)>/i',
		function ( $matches ) use ( $upload_dir, $site_url ) {
			$before_src = $matches[1];
			$src_url    = $matches[2];
			$after_src  = $matches[3];

			if ( ! is_local_url( $src_url ) ) {
				return $matches[0];
			}

			$src_path = $src_url;

			if ( strpos( $src_url, 'http' ) === 0 ) {
				$src_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $src_url );
				if ( ! file_exists( $src_path ) ) {
					$src_path = str_replace( $site_url . $upload_dir['baseurl'], $upload_dir['basedir'], $src_url );
				}
				if ( ! file_exists( $src_path ) ) {
					$protocol_relative = str_replace( array( 'http:', 'https:' ), '', $upload_dir['baseurl'] );
					$src_path          = str_replace( $protocol_relative, $upload_dir['basedir'], $src_url );
				}
			} elseif ( strpos( $src_url, '/' ) === 0 ) {
				$src_path = ABSPATH . ltrim( $src_url, '/' );
			} else {
				$src_path = $upload_dir['basedir'] . '/' . $src_url;
			}

			$src_path  = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $src_path );
			$real_path = realpath( $src_path );
			$src_path  = $real_path ? $real_path : $src_path;

			if ( file_exists( $src_path ) && is_file( $src_path ) ) {
				$file_info = pathinfo( $src_path );
				$webp_path = $file_info['dirname'] . DIRECTORY_SEPARATOR . $file_info['filename'] . '.webp';

				if ( ! file_exists( $webp_path ) ) {
					generate_webp( $src_path, $webp_path );
				}

				if ( file_exists( $webp_path ) ) {
					$webp_url = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
					$webp_url = str_replace( DIRECTORY_SEPARATOR, '/', $webp_url );
					return '<img' . $before_src . 'src="' . esc_url( $webp_url ) . '"' . $after_src . '>';
				}
			}

			return $matches[0];
		},
		$content
	);

	$content = preg_replace_callback(
		'/<img([^>]+)srcset=["\']([^"\']+)["\']([^>]*)>/i',
		function ( $matches ) use ( $upload_dir ) {
			$before_srcset = $matches[1];
			$srcset_value  = $matches[2];
			$after_srcset  = $matches[3];

			$srcset_parts = explode( ',', $srcset_value );
			$new_srcset   = array();

			foreach ( $srcset_parts as $part ) {
				$part = trim( $part );
				if ( empty( $part ) ) {
					continue;
				}

				if ( preg_match( '/^(.+?\.(?:jpg|jpeg|png))(?:\s+(\d+)w)?$/i', $part, $url_matches ) ) {
					$url   = trim( $url_matches[1] );
					$width = isset( $url_matches[2] ) ? $url_matches[2] : '';

					if ( ! is_local_url( $url ) ) {
						$new_srcset[] = $part;
						continue;
					}

					$src_path = $url;
					if ( strpos( $url, 'http' ) === 0 ) {
						$src_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
						if ( ! file_exists( $src_path ) ) {
							$src_path = str_replace( site_url() . $upload_dir['baseurl'], $upload_dir['basedir'], $url );
						}
					} elseif ( strpos( $url, '/' ) === 0 ) {
						$src_path = ABSPATH . ltrim( $url, '/' );
					} else {
						$src_path = $upload_dir['basedir'] . '/' . $url;
					}

					$src_path  = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $src_path );
					$real_path = realpath( $src_path );
					$src_path  = $real_path ? $real_path : $src_path;

					if ( file_exists( $src_path ) && is_file( $src_path ) ) {
						$file_info = pathinfo( $src_path );
						$webp_path = $file_info['dirname'] . DIRECTORY_SEPARATOR . $file_info['filename'] . '.webp';

						if ( ! file_exists( $webp_path ) ) {
							generate_webp( $src_path, $webp_path );
						}

						if ( file_exists( $webp_path ) ) {
							$webp_url     = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $webp_path );
							$webp_url     = str_replace( DIRECTORY_SEPARATOR, '/', $webp_url );
							$new_srcset[] = esc_url( $webp_url ) . ( $width ? ' ' . $width . 'w' : '' );
						} else {
							$new_srcset[] = $part;
						}
					} else {
						$new_srcset[] = $part;
					}
				} else {
					$new_srcset[] = $part;
				}
			}

			if ( ! empty( $new_srcset ) ) {
				return '<img' . $before_srcset . 'srcset="' . esc_attr( implode( ', ', $new_srcset ) ) . '"' . $after_srcset . '>';
			}

			return $matches[0];
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', __NAMESPACE__ . '\\replace_images_in_content_with_webp', 999 );

/**
 * Replace image URLs with WebP in final HTML output buffer.
 * This catches everything that other filters might miss.
 *
 * @param string $buffer The output buffer.
 * @return string Modified buffer.
 */
function replace_images_in_output_buffer( $buffer ) {
	if ( ! browser_supports_webp() || ! webp_supported() ) {
		return $buffer;
	}

	$buffer = preg_replace_callback(
		'/(<img[^>]+src=["\'])([^"\']+\.)(jpg|jpeg|png)(["\'][^>]*>)/i',
		function ( $matches ) {
			$url = $matches[2] . $matches[3];
			if ( ! is_local_url( $url ) ) {
				return $matches[0];
			}
			return $matches[1] . $matches[2] . 'webp' . $matches[4];
		},
		$buffer
	);

	$buffer = preg_replace_callback(
		'/(<img[^>]+srcset=["\'])([^"\']+)(["\'][^>]*>)/i',
		function ( $matches ) {
			$before = $matches[1];
			$srcset = $matches[2];
			$after  = $matches[3];

			$srcset_parts = explode( ',', $srcset );
			$new_parts    = array();

			foreach ( $srcset_parts as $part ) {
				$part = trim( $part );
				if ( empty( $part ) ) {
					continue;
				}

				if ( preg_match( '/^(.+?\.(?:jpg|jpeg|png))(?:\s+(\d+)w)?$/i', $part, $url_matches ) ) {
					$url = trim( $url_matches[1] );
					if ( is_local_url( $url ) ) {
						$width       = isset( $url_matches[2] ) ? ' ' . $url_matches[2] . 'w' : '';
						$new_parts[] = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $url ) . $width;
					} else {
						$new_parts[] = $part;
					}
				} else {
					$new_parts[] = $part;
				}
			}

			return $before . implode( ', ', $new_parts ) . $after;
		},
		$buffer
	);

	return $buffer;
}

if ( ! is_admin() && ! wp_is_json_request() ) {
	add_action(
		'template_redirect',
		function () {
			ob_start();
		},
		1
	);

		add_action(
			'shutdown',
			function () {
				$buffer = ob_get_clean();
				if ( $buffer ) {
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Buffer contains HTML that is already escaped.
					echo replace_images_in_output_buffer( $buffer );
				}
			},
			0
		);
}

/**
 * Add JavaScript fallback to replace image URLs with WebP on client side.
 */
function add_webp_replacement_script() {
	if ( is_admin() || ! webp_supported() ) {
		return;
	}
	?>
	<script>
	(function() {
		var siteUrl = <?php echo wp_json_encode( site_url() ); ?>;
		var homeUrl = <?php echo wp_json_encode( home_url() ); ?>;
		var siteHost = '';
		var homeHost = '';

		try {
			siteHost = new URL(siteUrl).hostname;
		} catch(e) {}

		try {
			homeHost = new URL(homeUrl).hostname;
		} catch(e) {}

		function isLocalUrl(url) {
			if (!url || typeof url !== 'string') {
				return false;
			}

			try {
				var urlObj = new URL(url, window.location.href);
				var hostname = urlObj.hostname;
				return hostname === siteHost || hostname === homeHost || hostname === window.location.hostname || hostname === '';
			} catch(e) {
				return url.indexOf('/') === 0 || url.indexOf(siteUrl) === 0 || url.indexOf(homeUrl) === 0;
			}
		}

		function replaceImagesWithWebP() {
			var images = document.querySelectorAll('img');
			images.forEach(function(img) {
				if (img.src && img.src.match(/\.(jpg|jpeg|png)(\?|$)/i)) {
					if (isLocalUrl(img.src)) {
						img.src = img.src.replace(/\.(jpg|jpeg|png)(\?|$)/i, '.webp$2');
					}
				}
				if (img.srcset) {
					img.srcset = img.srcset.replace(/([^\s,]+)\.(jpg|jpeg|png)(\?[^\s,]*)?(\s+\d+w)?/gi, function(match, url, ext, query, width) {
						if (isLocalUrl(url)) {
							return url.replace(/\.(jpg|jpeg|png)(\?|$)/i, '.webp$2') + (query || '') + (width || '');
						}
						return match;
					});
				}
			});
		}

		replaceImagesWithWebP();
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', replaceImagesWithWebP);
		}

		setTimeout(replaceImagesWithWebP, 100);
		setTimeout(replaceImagesWithWebP, 500);
		setTimeout(replaceImagesWithWebP, 1000);
	})();
	</script>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\\add_webp_replacement_script', 999 );
add_action( 'wp_head', __NAMESPACE__ . '\\add_webp_replacement_script', 999 );

add_action(
	'wp_print_scripts',
	function () {
		if ( is_admin() || ! webp_supported() ) {
			return;
		}
		?>
	<script>
	(function() {
		var siteUrl = <?php echo wp_json_encode( site_url() ); ?>;
		var homeUrl = <?php echo wp_json_encode( home_url() ); ?>;
		var siteHost = '';
		var homeHost = '';

		try {
			siteHost = new URL(siteUrl).hostname;
		} catch(e) {}

		try {
			homeHost = new URL(homeUrl).hostname;
		} catch(e) {}

		function isLocalUrl(url) {
			if (!url || typeof url !== 'string') {
				return false;
			}

			try {
				var urlObj = new URL(url, window.location.href);
				var hostname = urlObj.hostname;
				return hostname === siteHost || hostname === homeHost || hostname === window.location.hostname || hostname === '';
			} catch(e) {
				return url.indexOf('/') === 0 || url.indexOf(siteUrl) === 0 || url.indexOf(homeUrl) === 0;
			}
		}

		function replaceImagesWithWebP() {
			var images = document.querySelectorAll('img');
			images.forEach(function(img) {
				if (img.src && img.src.match(/\.(jpg|jpeg|png)(\?|$)/i)) {
					if (isLocalUrl(img.src)) {
						img.src = img.src.replace(/\.(jpg|jpeg|png)(\?|$)/i, '.webp$2');
					}
				}
				if (img.srcset) {
					img.srcset = img.srcset.replace(/([^\s,]+)\.(jpg|jpeg|png)(\?[^\s,]*)?(\s+\d+w)?/gi, function(match, url, ext, query, width) {
						if (isLocalUrl(url)) {
							return url.replace(/\.(jpg|jpeg|png)(\?|$)/i, '.webp$2') + (query || '') + (width || '');
						}
						return match;
					});
				}
			});
		}
		replaceImagesWithWebP();
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', replaceImagesWithWebP);
		}
		setTimeout(replaceImagesWithWebP, 100);
		setTimeout(replaceImagesWithWebP, 500);
		setTimeout(replaceImagesWithWebP, 1000);
	})();
	</script>
		<?php
	},
	1
);

/**
 * Register WP-CLI command for WebP regeneration.
 */
function register_webp_cli_command() {
	if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
		return;
	}

	if ( ! class_exists( 'WP_CLI' ) ) {
		return;
	}

	/**
	 * Regenerate WebP images.
	 *
	 * ## OPTIONS
	 *
	 * [--all]
	 * : Regenerate WebP for all existing JPEG and PNG images.
	 *
	 * [--attachment-id=<id>]
	 * : Regenerate WebP for a specific attachment ID.
	 *
	 * ## EXAMPLES
	 *
	 *     # Regenerate WebP for all images
	 *     wp webp regenerate --all
	 *
	 *     # Regenerate WebP for a specific attachment
	 *     wp webp regenerate --attachment-id=123
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 */
	$regenerate_webp = function ( $args, $assoc_args ) {
		if ( ! webp_supported() ) {
			\WP_CLI::error( 'WebP generation is not supported on this server. Please install Imagick with WebP support or GD library with imagewebp() function.' );
		}

		if ( isset( $assoc_args['all'] ) ) {
			\WP_CLI::line( 'Regenerating WebP for all images...' );

			$attachments = get_posts(
				array(
					'post_type'      => 'attachment',
					'post_mime_type' => array( 'image/jpeg', 'image/png' ),
					'posts_per_page' => -1,
					'fields'         => 'ids',
				)
			);

			if ( empty( $attachments ) ) {
				\WP_CLI::warning( 'No JPEG or PNG images found.' );
				return;
			}

			$progress = \WP_CLI\Utils\make_progress_bar( 'Regenerating WebP', count( $attachments ) );
			$success  = 0;
			$failed   = 0;

			foreach ( $attachments as $attachment_id ) {
				if ( regenerate_webp_for_attachment( $attachment_id ) ) {
					++$success;
				} else {
					++$failed;
				}
				$progress->tick();
			}

			$progress->finish();
			\WP_CLI::success( sprintf( 'Regenerated WebP for %d images. %d failed.', $success, $failed ) );
		} elseif ( isset( $assoc_args['attachment-id'] ) ) {
			$attachment_id = (int) $assoc_args['attachment-id'];
			\WP_CLI::line( sprintf( 'Regenerating WebP for attachment ID %d...', $attachment_id ) );

			if ( regenerate_webp_for_attachment( $attachment_id ) ) {
				\WP_CLI::success( 'WebP regenerated successfully.' );
			} else {
				\WP_CLI::error( 'Failed to regenerate WebP. Check that the attachment exists and is a JPEG or PNG image.' );
			}
		} else {
			\WP_CLI::error( 'Please specify --all or --attachment-id=<id>' );
		}
	};

	\WP_CLI::add_command( 'webp regenerate', $regenerate_webp );
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	register_webp_cli_command();
} else {
	add_action( 'cli_init', __NAMESPACE__ . '\\register_webp_cli_command' );
	add_action( 'init', __NAMESPACE__ . '\\register_webp_cli_command' );
}
