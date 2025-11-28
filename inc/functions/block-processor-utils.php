<?php
/**
 * Utility functions using WP_Block_Processor (WordPress 6.9+).
 *
 * These functions demonstrate efficient block processing using the new
 * streaming block parser API introduced in WordPress 6.9.
 *
 * @see https://make.wordpress.org/core/2025/11/19/introducing-the-streaming-block-parser-in-wordpress-6-9/
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Check if post content contains a block of a given type.
 *
 * Uses WP_Block_Processor for efficient streaming parsing (WordPress 6.9+).
 * Falls back to parse_blocks() for older versions.
 *
 * @param string $html       The post content HTML.
 * @param string ...$block_types Block types to check for (e.g., 'core/image', 'core/gallery').
 * @return bool True if any of the specified block types are found.
 */
function contains_block( $html, ...$block_types ) {
	if ( ! class_exists( 'WP_Block_Processor' ) ) {
		// Fallback for WordPress < 6.9.
		$blocks       = parse_blocks( $html );
		$check_blocks = function ( $blocks, $block_types ) use ( &$check_blocks ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) && in_array( $block['blockName'], $block_types, true ) ) {
					return true;
				}
				if ( ! empty( $block['innerBlocks'] ) && $check_blocks( $block['innerBlocks'], $block_types ) ) {
					return true;
				}
			}
			return false;
		};
		return $check_blocks( $blocks, $block_types );
	}

	// WordPress 6.9+ - Use streaming block processor.
	$processor = new \WP_Block_Processor( $html );
	while ( $processor->next_block() ) {
		if ( $processor->opens_block( ...$block_types ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Get the first block of a given type from post content.
 *
 * Uses WP_Block_Processor for efficient streaming parsing (WordPress 6.9+).
 *
 * @param string $html       The post content HTML.
 * @param string $block_type The block type to find (e.g., 'core/image').
 * @return string|null The block HTML if found, null otherwise.
 */
function get_first_block( $html, $block_type ) {
	if ( ! class_exists( 'WP_Block_Processor' ) ) {
		// Fallback for WordPress < 6.9.
		$blocks     = parse_blocks( $html );
		$find_block = function ( $blocks, $block_type ) use ( &$find_block ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) && $block['blockName'] === $block_type ) {
					return render_block( $block );
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$result = $find_block( $block['innerBlocks'], $block_type );
					if ( $result ) {
						return $result;
					}
				}
			}
			return null;
		};
		return $find_block( $blocks, $block_type );
	}

	// WordPress 6.9+ - Use streaming block processor.
	$processor = new \WP_Block_Processor( $html );
	while ( $processor->next_block() ) {
		if ( $processor->is_block_type( $block_type ) ) {
			return $processor->extract_full_block_and_advance();
		}
	}

	return null;
}

/**
 * Count blocks by type in post content.
 *
 * Uses WP_Block_Processor for efficient streaming parsing (WordPress 6.9+).
 *
 * @param string $html The post content HTML.
 * @return array Associative array of block types and their counts.
 */
function count_blocks_by_type( $html ) {
	$counts = array();

	if ( ! class_exists( 'WP_Block_Processor' ) ) {
		// Fallback for WordPress < 6.9.
		$blocks = parse_blocks( $html );
		$count  = function ( $blocks, &$counts ) use ( &$count ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) ) {
					$type            = $block['blockName'];
					$counts[ $type ] = 1 + ( $counts[ $type ] ?? 0 );
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$count( $block['innerBlocks'], $counts );
				}
			}
		};
		$count( $blocks, $counts );
		return $counts;
	}

	// WordPress 6.9+ - Use streaming block processor.
	$processor = new \WP_Block_Processor( $html );
	while ( $processor->next_block() ) {
		$type = $processor->get_block_type();
		if ( ! empty( $type ) ) {
			$counts[ $type ] = 1 + ( $counts[ $type ] ?? 0 );
		}
	}
	return $counts;
}

/**
 * Get all block types used in post content.
 *
 * Uses WP_Block_Processor for efficient streaming parsing (WordPress 6.9+).
 *
 * @param string $html The post content HTML.
 * @return array Array of unique block type names.
 */
function get_block_types_in_content( $html ) {
	$block_types = array();

	if ( ! class_exists( 'WP_Block_Processor' ) ) {
		// Fallback for WordPress < 6.9.
		$blocks  = parse_blocks( $html );
		$extract = function ( $blocks, &$block_types ) use ( &$extract ) {
			foreach ( $blocks as $block ) {
				if ( ! empty( $block['blockName'] ) ) {
					$block_types[] = $block['blockName'];
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$extract( $block['innerBlocks'], $block_types );
				}
			}
		};
		$extract( $blocks, $block_types );
		return array_unique( $block_types );
	}

	// WordPress 6.9+ - Use streaming block processor.
	$processor = new \WP_Block_Processor( $html );
	while ( $processor->next_block() ) {
		$block_type = $processor->get_block_type();
		if ( ! empty( $block_type ) ) {
			$block_types[] = $block_type;
		}
	}

	return array_unique( $block_types );
}

/**
 * Find the first image block within the first N blocks.
 *
 * Demonstrates early stopping capability of WP_Block_Processor.
 *
 * @param string $html     The post content HTML.
 * @param int    $max_blocks Maximum number of blocks to check (default: 10).
 * @return string|null The image block HTML if found, null otherwise.
 */
function get_first_image_in_first_blocks( $html, $max_blocks = 10 ) {
	if ( ! class_exists( 'WP_Block_Processor' ) ) {
		// Fallback for WordPress < 6.9 - less efficient, parses everything.
		$blocks = parse_blocks( $html );
		$count  = 0;
		$find   = function ( $blocks, $block_type, &$count, $max ) use ( &$find ) {
			foreach ( $blocks as $block ) {
				if ( $count >= $max ) {
					return null;
				}

				if ( ! empty( $block['blockName'] ) && $block['blockName'] === $block_type ) {
					return render_block( $block );
				}
				if ( ! empty( $block['innerBlocks'] ) ) {
					$result = $find( $block['innerBlocks'], $block_type, $count, $max );
					if ( $result ) {
						return $result;
					}
				}
				++$count;
			}
			return null;
		};
		return $find( $blocks, 'core/image', $count, $max_blocks );
	}

	// WordPress 6.9+ - Use streaming block processor with early stopping.
	$processor = new \WP_Block_Processor( $html );
	$remaining = $max_blocks;
	while ( $processor->next_block() && --$remaining >= 0 ) {
		if ( $processor->is_block_type( 'image' ) ) {
			return $processor->extract_full_block_and_advance();
		}
	}

	return null;
}
