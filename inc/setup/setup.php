<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @author WebDevStudios
 */
function setup() {
	/**
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wdsbt, refer to the
	 * README.md file in this theme to find and replace all
	 * references of wdsbt
	 */
	load_theme_textdomain( 'wdsbt', get_template_directory() . '/build/languages' );

	// Gutenberg support for full-width/wide alignment of supported blocks.
	add_theme_support( 'align-wide' );

	// Gutenberg editor styles support.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'build/style.css' );

	remove_action( 'wp_footer', 'the_block_template_skip_link' );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );
