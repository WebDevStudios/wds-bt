<?php
/**
 * Image Prioritizer.
 *
 * Prioritizes above-the-fold images by adding fetchpriority="high" attribute.
 * Inspired by WordPress Performance plugin: https://github.com/WordPress/performance/tree/trunk/plugins/image-prioritizer.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Normalize block attachment id to integer (core and bindings may pass WP_Post).
 *
 * @param array    $block         Parsed block.
 * @param array    $source_block  Unmodified block (unused; required by filter signature).
 * @param WP_Block $parent_block  Parent block (unused; required by filter signature).
 * @return array Block with normalized attrs.id when applicable.
 */
function normalize_block_attachment_id( $block, $source_block, $parent_block ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- Filter signature.
	if ( ! in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
		return $block;
	}
	if ( empty( $block['attrs']['id'] ) ) {
		return $block;
	}
	$id = $block['attrs']['id'];
	if ( $id instanceof \WP_Post ) {
		$block['attrs']['id'] = (int) $id->ID;
	}
	return $block;
}
add_filter( 'render_block_data', __NAMESPACE__ . '\\normalize_block_attachment_id', 1, 3 );

/**
 * Get above-the-fold image IDs from the current post/page.
 *
 * @param int $limit Maximum number of images to prioritize. Default 3.
 * @return array Array of attachment IDs.
 */
function get_above_fold_image_ids( $limit = 3 ) {
	$image_ids = array();

	// Prioritize featured image first.
	if ( has_post_thumbnail() ) {
		$image_ids[] = get_post_thumbnail_id();
	}

	$post = get_post();
	if ( ! $post || empty( $post->post_content ) ) {
		return array_slice( $image_ids, 0, $limit );
	}

	$content = $post->post_content;

	// Parse blocks to find image blocks.
	$blocks = parse_blocks( $content );
	$count  = 0;

	foreach ( $blocks as $block ) {
		if ( $count >= $limit ) {
			break;
		}

		// Check if this is an image or cover block.
		if ( in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
			// Get image ID from block attributes (may be int or WP_Post from bindings).
			if ( ! empty( $block['attrs']['id'] ) ) {
				$raw_id   = $block['attrs']['id'];
				$image_id = $raw_id instanceof \WP_Post ? (int) $raw_id->ID : (int) $raw_id;
				if ( $image_id && ! in_array( $image_id, $image_ids, true ) ) {
					$image_ids[] = $image_id;
					++$count;
				}
			}
		}

		// Also check nested blocks (for cover blocks with background images).
		if ( ! empty( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $inner_block ) {
				if ( $count >= $limit ) {
					break 2;
				}

				if ( in_array( $inner_block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
					if ( ! empty( $inner_block['attrs']['id'] ) ) {
						$raw_id   = $inner_block['attrs']['id'];
						$image_id = $raw_id instanceof \WP_Post ? (int) $raw_id->ID : (int) $raw_id;
						if ( $image_id && ! in_array( $image_id, $image_ids, true ) ) {
							$image_ids[] = $image_id;
							++$count;
						}
					}
				}
			}
		}
	}

	return array_slice( $image_ids, 0, $limit );
}

/**
 * Add fetchpriority="high" to above-the-fold images.
 *
 * @param array $attr       Array of image attributes.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified attributes.
 */
function prioritize_above_fold_images( $attr, $attachment_id ) {
	// Only process on singular pages.
	if ( ! is_singular() ) {
		return $attr;
	}

	// Skip if already has fetchpriority set.
	if ( isset( $attr['fetchpriority'] ) ) {
		return $attr;
	}

	// Normalize: filter can receive ID or WP_Post (e.g. in block context).
	$attachment_id = $attachment_id instanceof \WP_Post ? $attachment_id->ID : (int) $attachment_id;

	// Get above-the-fold image IDs.
	$above_fold_ids = get_above_fold_image_ids( 3 );

	if ( ! empty( $above_fold_ids ) && in_array( $attachment_id, $above_fold_ids, true ) ) {
		$attr['fetchpriority'] = 'high';

		// Remove lazy loading for above-the-fold images.
		if ( isset( $attr['loading'] ) && 'lazy' === $attr['loading'] ) {
			unset( $attr['loading'] );
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\prioritize_above_fold_images', 10, 2 );

/**
 * Prioritize above-the-fold images in block editor.
 *
 * @param array  $attributes Block attributes.
 * @param string $block_name Block name.
 * @return array Modified attributes.
 */
function prioritize_above_fold_block_images( $attributes, $block_name ) {
	// Only process on singular pages.
	if ( ! is_singular() ) {
		return $attributes;
	}

	// Only process image and cover blocks.
	if ( ! in_array( $block_name, array( 'core/image', 'core/cover' ), true ) ) {
		return $attributes;
	}

	// Skip if already has fetchpriority set.
	if ( isset( $attributes['fetchpriority'] ) ) {
		return $attributes;
	}

	// Get above-the-fold image IDs.
	$above_fold_ids = get_above_fold_image_ids( 3 );

	$block_image_id = isset( $attributes['id'] ) ? (int) $attributes['id'] : null;
	if ( $block_image_id && in_array( $block_image_id, $above_fold_ids, true ) ) {
		$attributes['fetchpriority'] = 'high';

		// Remove lazy loading for above-the-fold images.
		if ( isset( $attributes['loading'] ) ) {
			$attributes['loading'] = false;
		}
	}

	return $attributes;
}
add_filter( 'render_block_data', __NAMESPACE__ . '\\prioritize_above_fold_block_images', 10, 2 );

/**
 * Prioritize above-the-fold images in rendered HTML.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content.
 */
function prioritize_above_fold_images_in_html( $block_content, $block ) {
	// Only process on singular pages.
	if ( ! is_singular() ) {
		return $block_content;
	}

	// Only process image and cover blocks.
	if ( ! in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
		return $block_content;
	}

	// Get above-the-fold image IDs.
	$above_fold_ids = get_above_fold_image_ids( 3 );

	$block_image_id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : null;
	if ( ! $block_image_id || ! in_array( $block_image_id, $above_fold_ids, true ) ) {
		return $block_content;
	}

	// Add fetchpriority and remove lazy loading.
	$block_content = preg_replace_callback(
		'/<img([^>]*)>/i',
		function ( $matches ) {
			$img_attrs = $matches[1];

			// Skip if already has fetchpriority.
			if ( preg_match( '/fetchpriority=["\']high["\']/i', $img_attrs ) ) {
				return '<img' . $img_attrs . '>';
			}

			// Add fetchpriority.
			$img_attrs .= ' fetchpriority="high"';

			// Remove lazy loading.
			$img_attrs = preg_replace( '/\s*loading=["\']lazy["\']/i', '', $img_attrs );

			return '<img' . $img_attrs . '>';
		},
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\\prioritize_above_fold_images_in_html', 10, 2 );
