<?php
/**
 * WDS BT functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wdsbt
 * @author  WebDevStudios
 * @license GNU General Public License v3
 */

namespace WebDevStudios\wdsbt;

// Define a global path and url.
define( 'WebDevStudios\wdsbt\ROOT_PATH', trailingslashit( get_template_directory() ) );
define( 'WebDevStudios\wdsbt\ROOT_URL', trailingslashit( get_template_directory_uri() ) );

/**
 * Get all the include files for the theme.
 *
 * @author WebDevStudios
 */
function include_inc_files() {
	$files = array(
		'inc/functions/', // Custom functions that act independently of the theme templates.
		'inc/hooks/', // Load custom filters and hooks.
		'inc/setup/', // Theme setup.
		'inc/performance', // Performance filters.
	);

	foreach ( $files as $include ) {
		$include = trailingslashit( get_template_directory() ) . $include;

		// Allows inclusion of individual files or all .php files in a directory.
		if ( is_dir( $include ) ) {
			foreach ( glob( $include . '/*.php' ) as $file ) {
				require $file;
			}
		} else {
			require $include;
		}
	}
}

include_inc_files();
