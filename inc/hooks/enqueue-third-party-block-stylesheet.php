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

	$block_names = array();

	if ( class_exists( 'WP_Block_Processor' ) ) {
		$processor = new \WP_Block_Processor( $post->post_content );
		while ( $processor->next_block() ) {
			$block_type = $processor->get_block_type();
			if ( ! empty( $block_type ) && 0 !== strpos( $block_type, 'core/' ) ) {
				$block_names[] = $block_type;
			}
		}
	} else {
		$blocks              = parse_blocks( $post->post_content );
		$extract_block_names = function ( $blocks, &$block_names ) use ( &$extract_block_names ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) && 0 !== strpos( $block['blockName'], 'core/' ) ) {
					$block_names[] = $block['blockName'];
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$extract_block_names( $block['innerBlocks'], $block_names );
				}
			}
		};
		$extract_block_names( $blocks, $block_names );
	}

	$block_names = array_unique( $block_names );

	foreach ( $block_names as $block_name ) {
		$parts      = explode( '/', $block_name );
		$file_name  = end( $parts );
		$style_file = get_theme_file_path( '/build/css/blocks/' . $file_name . '.css' );
		if ( file_exists( $style_file ) ) {
			$handle = 'wdsbt-' . $file_name . '-style';
			wp_enqueue_style(
				$handle,
				get_theme_file_uri( '/build/css/blocks/' . $file_name . '.css' ),
				array(),
				(string) filemtime( $style_file )
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_third_party_block_overrides' );
