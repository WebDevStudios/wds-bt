<?php
/**
 * Enqueue third‑party block override styles.
 *
 * These styles are built into build/css/blocks using a naming convention where the file
 * name is the block name without its namespace. For example, for a block "wdsbt/jctest",
 * the function will look for "jctest.css" in the build/css/blocks folder.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Enqueue third‑party block override styles.
 *
 * @return void
 */
function enqueue_third_party_block_overrides() {
	if ( ! is_singular() ) {
		return;
	}

	global $post;
	if ( empty( $post->post_content ) ) {
		return;
	}

	// Parse blocks efficiently with WP_Block_Processor (WordPress 6.9+), fallback to parse_blocks() for older versions.
	$block_names = array();

	if ( class_exists( 'WP_Block_Processor' ) ) {
		// WordPress 6.9+ - Use streaming block processor for better performance.
		$processor = new \WP_Block_Processor( $post->post_content );
		while ( $processor->next_block() ) {
			$block_type = $processor->get_block_type();
			// Only process non-core blocks.
			if ( ! empty( $block_type ) && 0 !== strpos( $block_type, 'core/' ) ) {
				$block_names[] = $block_type;
			}
		}
	} else {
		// Fallback for WordPress < 6.9.
		$blocks              = parse_blocks( $post->post_content );
		$extract_block_names = function ( $blocks, &$block_names ) use ( &$extract_block_names ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) ) {
					// Only process non-core blocks.
					if ( 0 !== strpos( $block['blockName'], 'core/' ) ) {
						$block_names[] = $block['blockName'];
					}
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$extract_block_names( $block['innerBlocks'], $block_names );
				}
			}
		};
		$extract_block_names( $blocks, $block_names );
	}

	$block_names = array_unique( $block_names );

	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'Third‑party blocks found: ' . print_r( $block_names, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
	}

	foreach ( $block_names as $block_name ) {
		// Convert "namespace/blockname" into just "blockname".
		$parts      = explode( '/', $block_name );
		$file_name  = end( $parts );
		$style_file = get_theme_file_path( '/build/css/blocks/' . $file_name . '.css' );
		if ( file_exists( $style_file ) ) {
			$handle = 'wdsbt-' . $file_name . '-style';
			wp_enqueue_style(
				$handle,
				get_theme_file_uri( '/build/css/blocks/' . $file_name . '.css' ),
				array(),
				filemtime( $style_file )
			);

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "Enqueued style for block {$block_name} as {$handle}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( "No style file found for block {$block_name}; looked for {$style_file}" ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_third_party_block_overrides' );
