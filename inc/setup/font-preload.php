<?php
/**
 * Auto-generated font preload links.
 */
function wdsbt_font_preload_links() {
	$preload_links = [
		'fonts/body/oxygen-v15-latin-300.woff2' => 'font/woff2',
		'fonts/headline/Inter.woff2'            => 'font/woff2',
		'fonts/mono/roboto-mono.woff2'          => 'font/woff2',
	];

	foreach ( $preload_links as $href => $as ) {
		echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() ) . '/build/' . esc_attr( $href ) . '" as="' . esc_attr( $as ) . '" crossorigin>';
	}
}
