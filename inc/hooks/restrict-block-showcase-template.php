<?php
/**
 * Restrict Block Showcase template to administrators only.
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Restrict Block Showcase template access to administrators only.
 *
 * @param string $template The template file path.
 * @return string The template file path or 404 template if access denied.
 */
function restrict_block_showcase_template( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	$page_template = get_page_template_slug();

	if ( 'page-block-showcase.html' === $page_template ) {
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
add_filter( 'template_include', __NAMESPACE__ . '\restrict_block_showcase_template', 99 );

/**
 * Hide Block Showcase template from non-admin users in the page template dropdown.
 *
 * @param array $post_templates Array of page templates.
 * @return array Filtered array of page templates.
 */
function hide_block_showcase_template_from_non_admins( $post_templates ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		unset( $post_templates['page-block-showcase.html'] );
	}

	return $post_templates;
}
add_filter( 'theme_page_templates', __NAMESPACE__ . '\hide_block_showcase_template_from_non_admins' );
