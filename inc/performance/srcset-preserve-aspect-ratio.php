<?php
/**
 * Drop hard-cropped subsizes from responsive srcset when they do not match the
 * full attachment aspect ratio (e.g. 1160×870 original vs 1160×675 in srcset).
 *
 * Browsers pick by `w` descriptor + `sizes`; a same-width cropped file wins over
 * the uncropped `src` and looks “cropped everywhere.” This runs before WebP URL swaps.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Compare aspect ratio of a subsize to the full image (tolerant float compare).
 *
 * @param int $full_w Full image width.
 * @param int $full_h Full image height.
 * @param int $sub_w  Subsize width.
 * @param int $sub_h  Subsize height.
 * @return bool True if ratios match within tolerance.
 */
function attachment_subsize_matches_full_aspect( $full_w, $full_h, $sub_w, $sub_h ) {
	$full_w = (int) $full_w;
	$full_h = (int) $full_h;
	$sub_w  = (int) $sub_w;
	$sub_h  = (int) $sub_h;

	if ( $full_w < 1 || $full_h < 1 || $sub_w < 1 || $sub_h < 1 ) {
		return true;
	}

	$full_ratio = $full_w / $full_h;
	$sub_ratio  = $sub_w / $sub_h;

	// ~1.5% relative tolerance for rounding (e.g. soft-scaled JPEG dimensions).
	$delta = abs( $sub_ratio - $full_ratio ) / max( $full_ratio, 0.0001 );

	return $delta <= 0.015;
}

/**
 * Remove srcset candidates whose stored dimensions do not match full aspect.
 *
 * @param array  $sources       One or more arrays of source data.
 * @param array  $size_array    Width and height of the image.
 * @param string $image_src     The src of the image.
 * @param array  $image_meta    Attachment metadata.
 * @param int    $attachment_id Image attachment ID.
 * @return array
 */
function srcset_remove_mismatched_aspect_subsizes( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	if ( empty( $sources ) || ! is_array( $sources ) ) {
		return $sources;
	}

	$attachment_id = (int) $attachment_id;
	$meta          = ( $attachment_id > 0 ) ? wp_get_attachment_metadata( $attachment_id ) : $image_meta;
	if ( empty( $meta ) || ! is_array( $meta ) ) {
		$meta = $image_meta;
	}

	if ( empty( $meta['width'] ) || empty( $meta['height'] ) ) {
		return $sources;
	}

	$sources_before = $sources;

	$full_w    = (int) $meta['width'];
	$full_h    = (int) $meta['height'];
	$sizes     = isset( $meta['sizes'] ) && is_array( $meta['sizes'] ) ? $meta['sizes'] : array();
	$main      = isset( $meta['file'] ) ? (string) $meta['file'] : '';
	$main_base = $main ? basename( $main ) : '';

	foreach ( $sources as $key => $source ) {
		if ( empty( $source['url'] ) ) {
			continue;
		}

		$path = wp_parse_url( $source['url'], PHP_URL_PATH );
		if ( ! $path ) {
			continue;
		}

		$basename = basename( $path );
		$sub_w    = 0;
		$sub_h    = 0;

		if ( $main_base && $basename === $main_base ) {
			$sub_w = $full_w;
			$sub_h = $full_h;
		} else {
			foreach ( $sizes as $size_data ) {
				if ( ! empty( $size_data['file'] ) && $size_data['file'] === $basename ) {
					$sub_w = isset( $size_data['width'] ) ? (int) $size_data['width'] : 0;
					$sub_h = isset( $size_data['height'] ) ? (int) $size_data['height'] : 0;
					break;
				}
			}
		}

		if ( $sub_w < 1 || $sub_h < 1 ) {
			continue;
		}

		if ( ! attachment_subsize_matches_full_aspect( $full_w, $full_h, $sub_w, $sub_h ) ) {
			unset( $sources[ $key ] );
		}
	}

	// If nothing remains (unlikely), return unmodified srcset.
	if ( empty( $sources ) ) {
		return $sources_before;
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', __NAMESPACE__ . '\\srcset_remove_mismatched_aspect_subsizes', 5, 5 );
