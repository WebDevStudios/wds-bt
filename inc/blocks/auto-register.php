<?php
/**
 * Auto-register blocks from template-parts/blocks directory.
 *
 * @package wdsbt
 */

/**
 * Register all blocks found in assets/blocks using block.json metadata.
 */
add_action(
	'init',
	function () {
		$blocks_dir = get_stylesheet_directory() . '/template-parts/blocks';

		if ( ! is_dir( $blocks_dir ) ) {
			return;
		}

		// Scan for block directories
		$items = scandir( $blocks_dir );

		foreach ( $items as $item ) {
			// Skip current and parent directory references.
			if ( '.' === $item || '..' === $item ) {
				continue;
			}

			$block_dir = $blocks_dir . '/' . $item;

			// Only process directories that contain block.json.
			if ( is_dir( $block_dir ) && file_exists( $block_dir . '/block.json' ) ) {
				register_block_type( $block_dir );
			}
		}
	}
);
