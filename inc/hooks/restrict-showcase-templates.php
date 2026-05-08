<?php
/**
 * Restrict dev block showcase template to administrators only.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Page template basenames reserved for admins.
 *
 * @return string[]
 */
function get_admin_only_showcase_page_templates() {
	return array(
		'page-block-showcase.html',
	);
}

/**
 * Forces 404 when a non-admin hits a showcase page template.
 *
 * @param string $template Resolved template path.
 * @return string
 */
function restrict_showcase_templates( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	$page_template = get_page_template_slug();

	if ( in_array( $page_template, get_admin_only_showcase_page_templates(), true ) ) {
		if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
			nocache_headers();
			return get_query_template( '404' );
		}
	}

	return $template;
}
add_filter( 'template_include', __NAMESPACE__ . '\restrict_showcase_templates', 99 );

/**
 * Hides showcase templates from non-admins in the template dropdown.
 *
 * @param array $post_templates Page templates keyed by file basename.
 * @return array
 */
function hide_showcase_templates_from_non_admins( $post_templates ) {
	if ( current_user_can( 'manage_options' ) ) {
		return $post_templates;
	}

	foreach ( get_admin_only_showcase_page_templates() as $file ) {
		unset( $post_templates[ $file ] );
	}

	return $post_templates;
}
add_filter( 'theme_page_templates', __NAMESPACE__ . '\hide_showcase_templates_from_non_admins' );
