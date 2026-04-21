<?php
/**
 * Dominant Color Images.
 *
 * Calculates and stores the dominant color for uploaded images,
 * then uses it as a placeholder background while images load.
 * Inspired by WordPress Performance plugin: https://github.com/WordPress/performance/tree/trunk/plugins/dominant-color-images.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Dominant color hex from file.
 *
 * @param string $file_path Absolute path to image.
 * @return string|false Hex or false.
 */
function calculate_dominant_color( $file_path ) {
	if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
		return false;
	}

	$image_info = wp_getimagesize( $file_path );
	if ( ! $image_info ) {
		return false;
	}

	$mime_type = $image_info['mime'] ?? '';

	if ( extension_loaded( 'imagick' ) && class_exists( 'Imagick' ) ) {
		try {
			$imagick = new \Imagick( $file_path );
			$imagick->resizeImage( 1, 1, \Imagick::FILTER_LANCZOS, 1 );
			$pixel = $imagick->getImagePixelColor( 0, 0 );
			$color = $pixel->getColor();

			$hex = sprintf(
				'#%02x%02x%02x',
				$color['r'],
				$color['g'],
				$color['b']
			);

			$imagick->clear();
			$imagick->destroy();

			return $hex;
		} catch ( \Exception $e ) {
			// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			error_log( 'Imagick failed to process image: ' . $e->getMessage() );
		}
	}

	if ( extension_loaded( 'gd' ) && function_exists( 'imagecreatefromstring' ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading local file path is safe
		$image_data = file_get_contents( $file_path );
		if ( false === $image_data ) {
			return false;
		}

		$image = imagecreatefromstring( $image_data );
		if ( false === $image ) {
			return false;
		}

		$thumb = imagecreatetruecolor( 1, 1 );
		if ( false === $thumb ) {
			return false;
		}

		imagecopyresampled( $thumb, $image, 0, 0, 0, 0, 1, 1, imagesx( $image ), imagesy( $image ) );
		$main_color = imagecolorat( $thumb, 0, 0 );
		$rgb        = imagecolorsforindex( $thumb, $main_color );

		return sprintf( '#%02x%02x%02x', $rgb['red'], $rgb['green'], $rgb['blue'] );
	}

	return false;
}

/**
 * Store dominant color meta on upload.
 *
 * @param int $attachment_id Attachment ID.
 * @return void
 */
function save_dominant_color( $attachment_id ) {
	$mime_type = get_post_mime_type( $attachment_id );
	if ( ! $mime_type || strpos( $mime_type, 'image/' ) !== 0 ) {
		return;
	}

	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path ) {
		return;
	}

	$dominant_color = calculate_dominant_color( $file_path );
	if ( $dominant_color ) {
		update_post_meta( $attachment_id, '_dominant_color', $dominant_color );
	}
}
add_action( 'add_attachment', __NAMESPACE__ . '\\save_dominant_color' );
add_action( 'edit_attachment', __NAMESPACE__ . '\\save_dominant_color' );

/**
 * Read stored dominant color.
 *
 * @param int $attachment_id Attachment ID.
 * @return string|false
 */
function get_dominant_color( $attachment_id ) {
	return get_post_meta( $attachment_id, '_dominant_color', true );
}

/**
 * Image attributes: background placeholder.
 *
 * @param array $attr            Image attributes.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function add_dominant_color_placeholder( $attr, $attachment_id ) {
	$dominant_color = get_dominant_color( $attachment_id );
	if ( ! $dominant_color ) {
		return $attr;
	}

	$style         = isset( $attr['style'] ) ? $attr['style'] : '';
	$style         = rtrim( $style, ';' ) . '; background-color: ' . esc_attr( $dominant_color ) . ';';
	$attr['style'] = $style;

	$attr['data-dominant-color'] = esc_attr( $dominant_color );

	$class         = isset( $attr['class'] ) ? $attr['class'] : '';
	$attr['class'] = trim( $class . ' has-dominant-color' );

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\add_dominant_color_placeholder', 10, 2 );

/**
 * Block render: add placeholder to core/image imgs.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function add_dominant_color_to_image_blocks( $block_content, $block ) {
	if ( ! in_array( $block['blockName'] ?? '', array( 'core/image', 'core/cover' ), true ) ) {
		return $block_content;
	}

	if ( 'core/cover' === ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}

	$attachment_id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
	if ( ! $attachment_id ) {
		return $block_content;
	}

	$dominant_color = get_dominant_color( $attachment_id );
	if ( ! $dominant_color ) {
		return $block_content;
	}

	$block_content = preg_replace_callback(
		'/<img([^>]*)>/i',
		function ( $matches ) use ( $dominant_color ) {
			$img_attrs = $matches[1];

			if ( preg_match( '/style=["\']([^"\']*)["\']/', $img_attrs, $style_matches ) ) {
				$existing_style = $style_matches[1];
				$new_style      = rtrim( $existing_style, ';' ) . '; background-color: ' . esc_attr( $dominant_color ) . ';';
				$img_attrs      = preg_replace( '/style=["\'][^"\']*["\']/', 'style="' . esc_attr( $new_style ) . '"', $img_attrs );
			} else {
				$img_attrs .= ' style="background-color: ' . esc_attr( $dominant_color ) . ';"';
			}

			if ( ! preg_match( '/data-dominant-color=["\'][^"\']*["\']/', $img_attrs ) ) {
				$img_attrs .= ' data-dominant-color="' . esc_attr( $dominant_color ) . '"';
			}

			if ( ! preg_match( '/class=["\'][^"\']*has-dominant-color[^"\']*["\']/', $img_attrs ) ) {
				if ( preg_match( '/class=["\']([^"\']*)["\']/', $img_attrs, $class_matches ) ) {
					$existing_class = $class_matches[1];
					$new_class      = trim( $existing_class . ' has-dominant-color' );
					$img_attrs      = preg_replace( '/class=["\'][^"\']*["\']/', 'class="' . esc_attr( $new_class ) . '"', $img_attrs );
				} else {
					$img_attrs .= ' class="has-dominant-color"';
				}
			}

			return '<img' . $img_attrs . '>';
		},
		$block_content
	);

	return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\\add_dominant_color_to_image_blocks', 10, 2 );

/**
 * Front: inline placeholder CSS.
 *
 * @return void
 */
function enqueue_dominant_color_styles() {
	$css = '
		.has-dominant-color {
			transition: opacity 0.3s ease-in-out;
		}
		.has-dominant-color[loading="lazy"] {
			opacity: 0.8;
		}
		.has-dominant-color:not([loading="lazy"]) {
			opacity: 1;
		}
		.has-dominant-color.loaded {
			opacity: 1;
		}
	';

	$handle  = 'wdsbt-dominant-color';
	$version = wp_get_theme( get_template() )->get( 'Version' );
	$version = $version ? (string) $version : '1.0.0';
	wp_register_style( $handle, false, array(), $version );
	wp_enqueue_style( $handle );
	wp_add_inline_style( $handle, $css );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_dominant_color_styles', 20 );

/**
 * Front: load handler inline script.
 *
 * @return void
 */
function enqueue_dominant_color_script() {
	$script = "
		(function() {
			document.addEventListener('DOMContentLoaded', function() {
				const images = document.querySelectorAll('img.has-dominant-color');
				images.forEach(function(img) {
					if (img.complete) {
						img.classList.add('loaded');
					} else {
						img.addEventListener('load', function() {
							this.classList.add('loaded');
						});
					}
				});
			});
		})();
	";

	$handle  = 'wdsbt-dominant-color';
	$version = wp_get_theme( get_template() )->get( 'Version' );
	$version = $version ? (string) $version : '1.0.0';
	wp_register_script( $handle, false, array(), $version, true );
	wp_enqueue_script( $handle );
	wp_add_inline_script( $handle, $script, 'after' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_dominant_color_script', 20 );
