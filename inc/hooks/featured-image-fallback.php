<?php
/**
 * Featured Image fallback.
 *
 * Sets a default image for when a post does not have a featured image.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Filters the post thumbnail HTML.
 *
 * @param string $html The post thumbnail HTML.
 * @param int    $post_id The post ID.
 * @param int    $post_thumbnail_id The post thumbnail ID, or 0 if there isn’t one.
 * @return string The modified post thumbnail HTML.
 */
function set_fallback_post_thumbnail_html( $html, $post_id, $post_thumbnail_id ) {
	if ( empty( $post_thumbnail_id ) ) {
		$default_image_url = 'https://placehold.co/1920x1080.png';
		$html              = '<img src="' . esc_url( $default_image_url ) . '" class="wp-post-image" alt="Default Image"/>';
	}
	return $html;
}
add_filter( 'post_thumbnail_html', __NAMESPACE__ . '\set_fallback_post_thumbnail_html', 10, 5 );

/**
 * Filters the post thumbnail URL.
 *
 * @param string|false $thumbnail_url Post thumbnail URL or false if the post does not exist.
 * @return string|false The modified post thumbnail URL.
 */
function set_fallback_post_thumbnail_url( $thumbnail_url ) {
	if ( empty( $thumbnail_url ) ) {
		$thumbnail_url = 'https://placehold.co/1920x1080.png';
	}
	return $thumbnail_url;
}
add_filter( 'post_thumbnail_url', __NAMESPACE__ . '\set_fallback_post_thumbnail_url', 10, 2 );
