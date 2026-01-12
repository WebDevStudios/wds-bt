<?php
/**
 * Preload Largest Contentful Paint (LCP) image.
 *
 * Automatically detects and preloads the LCP image to improve page performance.
 * Prioritizes featured images, then falls back to the first large image in content.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Get the LCP image ID from the current post/page.
 *
 * Uses WP_Block_Processor for efficient streaming parsing (WordPress 6.9+).
 * Falls back to parse_blocks() for older versions.
 *
 * @return int|null The attachment ID if found, null otherwise.
 */
function get_lcp_image_id() {
	if ( has_post_thumbnail() ) {
		return get_post_thumbnail_id();
	}

	$post = get_post();
	if ( ! $post || empty( $post->post_content ) ) {
		return null;
	}

	$content = $post->post_content;

	// Parse blocks to find image blocks.
	$blocks = parse_blocks( $content );

	$find_image_in_blocks = function ( $blocks ) use ( &$find_image_in_blocks ) {
		foreach ( $blocks as $block ) {
			if ( 'core/image' === ( $block['blockName'] ?? '' ) ) {
				$attrs = $block['attrs'] ?? array();
				if ( ! empty( $attrs['id'] ) ) {
					return (int) $attrs['id'];
				}
			}
			if ( 'core/cover' === ( $block['blockName'] ?? '' ) ) {
				$attrs = $block['attrs'] ?? array();
				if ( ! empty( $attrs['id'] ) ) {
					return (int) $attrs['id'];
				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$result = $find_image_in_blocks( $block['innerBlocks'] );
				if ( $result ) {
					return $result;
				}
			}
		}
		return null;
	};

	return $find_image_in_blocks( $blocks );
}

/**
 * Get the LCP image URL from the current post/page.
 *
 * @return string|null The image URL if found, null otherwise.
 */
function get_lcp_image_url() {
	$image_id = get_lcp_image_id();
	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, 'full' );
		if ( $image_url ) {
			return $image_url;
		}
	}

	$post = get_post();
	if ( ! $post || empty( $post->post_content ) ) {
		return null;
	}

	$content = $post->post_content;

	$image_block = get_first_block( $content, 'core/image' );
	if ( $image_block ) {
		if ( preg_match( '/src=["\']([^"\']+)["\']/', $image_block, $matches ) ) {
			return $matches[1];
		}
		if ( preg_match( '/srcset=["\']([^"\']+)["\']/', $image_block, $matches ) ) {
			$srcset = $matches[1];
			if ( preg_match( '/^([^\s,]+)/', $srcset, $url_matches ) ) {
				return $url_matches[1];
			}
		}
	}

	if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches ) ) {
		return $matches[1];
	}

	return null;
}

/**
 * Output preload link for LCP image.
 */
function preload_lcp_image() {
	if ( ! is_singular() ) {
		return;
	}

	$image_url = get_lcp_image_url();
	if ( ! $image_url ) {
		return;
	}

	if ( strpos( $image_url, 'data:' ) === 0 ) {
		return;
	}

	if ( ! preg_match( '/^(https?:)?\/\//', $image_url ) ) {
		if ( '/' === $image_url[0] ) {
			$image_url = home_url( $image_url );
		} else {
			$image_url = site_url( $image_url );
		}
	}

	printf(
		'<link rel="preload" as="image" href="%s" fetchpriority="high">%s',
		esc_url( $image_url ),
		"\n"
	);
}
add_action( 'wp_head', __NAMESPACE__ . '\\preload_lcp_image', 1 );

/**
 * Optimize LCP image attributes: add fetchpriority="high" and disable lazy-loading.
 *
 * @param array $attr Array of image attributes.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified attributes.
 */
function optimize_lcp_image_attributes( $attr, $attachment_id ) {
	if ( ! is_singular() ) {
		return $attr;
	}

	$lcp_image_id = get_lcp_image_id();
	if ( ! $lcp_image_id || (int) $attachment_id !== (int) $lcp_image_id ) {
		return $attr;
	}

	$attr['fetchpriority'] = 'high';

	if ( isset( $attr['loading'] ) && 'lazy' === $attr['loading'] ) {
		unset( $attr['loading'] );
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\optimize_lcp_image_attributes', 10, 2 );

/**
 * Optimize LCP image block attributes: disable lazy-loading and add fetchpriority.
 *
 * @param array  $attributes Block attributes.
 * @param string $block_name Block name.
 * @return array Modified attributes.
 */
function optimize_lcp_image_block_attributes( $attributes, $block_name ) {
	if ( ! is_singular() ) {
		return $attributes;
	}

	if ( ! in_array( $block_name, array( 'core/image', 'core/cover' ), true ) ) {
		return $attributes;
	}

	$lcp_image_id = get_lcp_image_id();
	if ( ! $lcp_image_id ) {
		return $attributes;
	}

	$block_image_id = isset( $attributes['id'] ) ? (int) $attributes['id'] : null;
	if ( ! $block_image_id || $block_image_id !== $lcp_image_id ) {
		return $attributes;
	}

	$attributes['loading'] = false;

	$attributes['fetchpriority'] = 'high';

	return $attributes;
}
add_filter( 'render_block_data', __NAMESPACE__ . '\\optimize_lcp_image_block_attributes', 10, 2 );

/**
 * Optimize LCP image in rendered HTML: add fetchpriority and remove lazy-loading.
 *
 * @param string $block_content The block content about to be appended.
 * @param array  $block         The full block, including name and attributes.
 * @return string Modified block content.
 */
function optimize_lcp_image_in_html( $block_content, $block ) {
	if ( ! is_singular() ) {
		return $block_content;
	}

	if ( ! in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
		return $block_content;
	}

	$lcp_image_id = get_lcp_image_id();
	if ( ! $lcp_image_id ) {
		return $block_content;
	}

	$block_image_id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : null;
	if ( ! $block_image_id || $block_image_id !== $lcp_image_id ) {
		return $block_content;
	}

	$block_content = preg_replace_callback(
		'/<img([^>]*)>/i',
		function ( $matches ) {
			$img_attrs = $matches[1];

			if ( ! preg_match( '/fetchpriority=["\']high["\']/i', $img_attrs ) ) {
				$img_attrs .= ' fetchpriority="high"';
			}

			$img_attrs = preg_replace( '/\s*loading=["\']lazy["\']/i', '', $img_attrs );

			return '<img' . $img_attrs . '>';
		},
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\\optimize_lcp_image_in_html', 10, 2 );
