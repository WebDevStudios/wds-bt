<?php
/**
 * Image Prioritizer.
 *
 * Prioritizes above-the-fold images by adding fetchpriority="high" attribute.
 * Inspired by WordPress Performance plugin: https://github.com/WordPress/performance/tree/trunk/plugins/image-prioritizer.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

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

	// Use WP_Block_Processor for efficient parsing (WordPress 6.9+).
	if ( class_exists( 'WP_Block_Processor' ) ) {
		$processor = new \WP_Block_Processor( $content );
		$count     = 0;

		while ( $processor->next_block() && $count < $limit ) {
			if ( $processor->is_block_type( 'image' ) || $processor->is_block_type( 'cover' ) ) {
				$block_html = $processor->extract_full_block_and_advance();
				if ( $block_html ) {
					if ( preg_match( '/<!--\s*wp:core\/(?:image|cover)\s+(\{.*?\})\s*-->/s', $block_html, $matches ) ) {
						$attrs_json = $matches[1];
						$attrs      = json_decode( $attrs_json, true );
						if ( is_array( $attrs ) && ! empty( $attrs['id'] ) ) {
							$image_id = (int) $attrs['id'];
							if ( ! in_array( $image_id, $image_ids, true ) ) {
								$image_ids[] = $image_id;
								++$count;
							}
						}
					} else {
						$blocks = parse_blocks( $block_html );
						if ( ! empty( $blocks[0]['attrs']['id'] ) ) {
							$image_id = (int) $blocks[0]['attrs']['id'];
							if ( ! in_array( $image_id, $image_ids, true ) ) {
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

	// Fallback for WordPress < 6.9.
	$blocks = parse_blocks( $content );

	$find_images_in_blocks = function ( $blocks ) use ( &$find_images_in_blocks, &$image_ids, $limit ) {
		foreach ( $blocks as $block ) {
			if ( count( $image_ids ) >= $limit ) {
				break;
			}

			if ( in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
				$attrs = $block['attrs'] ?? array();
				if ( ! empty( $attrs['id'] ) ) {
					$image_id = (int) $attrs['id'];
					if ( ! in_array( $image_id, $image_ids, true ) ) {
						$image_ids[] = $image_id;
					}
				}
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$find_images_in_blocks( $block['innerBlocks'] );
			}
		}
	};

	$find_images_in_blocks( $blocks );

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

	// Get above-the-fold image IDs.
	$above_fold_ids = get_above_fold_image_ids( 3 );

	if ( ! empty( $above_fold_ids ) && in_array( (int) $attachment_id, $above_fold_ids, true ) ) {
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
