<?php
/**
 * Preload styles and scripts.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Preload styles and scripts.
 *
 * @author WebDevStudios
 */
function preload_scripts() {
	$asset_file_path = dirname( __DIR__ ) . '/build/index.asset.php';

	if ( is_readable( $asset_file_path ) ) {
		$asset_file = include $asset_file_path;
	} else {
		$asset_file = array(
			'version'      => '1.0.0',
			'dependencies' => array( 'wp-polyfill' ),
		);
	}

	?>
	<link rel="preload" href="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/build/css/style.css?ver=<?php echo esc_html( $asset_file['version'] ); ?>" as="style">
	<link rel="preload" href="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/build/js/index.js?ver=<?php echo esc_html( $asset_file['version'] ); ?>" as="script">
	<?php
}
add_action( 'wp_head', __NAMESPACE__ . '\preload_scripts', 1 );
