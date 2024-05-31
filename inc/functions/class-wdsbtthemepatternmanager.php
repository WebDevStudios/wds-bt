<?php
/**
 * Theme Pattern Manager
 *
 * This file contains the code to add import and export buttons for block patterns
 * to the list of posts showing the post type 'wp_block'.
 *
 * @package YourTheme
 * @subpackage WdsbtThemePatternManager
 * @since 1.0.0
 */

/**
 * Class WdsbtThemePatternManager
 *
 * Handles the import and export functionality for block patterns.
 *
 * @since 1.0.0
 */
class WdsbtThemePatternManager {

	/**
	 * WdsbtThemePatternManager constructor.
	 *
	 * Registers the necessary hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer-edit.php', array( $this, 'add_import_export_buttons' ) );
		add_action( 'wp_ajax_import_patterns', array( $this, 'import_patterns' ) );
		add_action( 'wp_ajax_export_patterns', array( $this, 'export_patterns' ) );
	}

	/**
	 * Enqueue scripts for the admin area.
	 *
	 * @param string $hook The current admin page.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		if ( 'edit.php' !== $hook || get_current_screen()->post_type !== 'wp_block' ) {
			return;
		}
		wp_enqueue_script(
			'pattern-manager-script',
			get_template_directory_uri() . '/pattern-manager.js',
			array( 'jquery' ),
			'1.0',
			true
		);
		wp_localize_script(
			'pattern-manager-script',
			'PatternManagerAjax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'pattern_manager_nonce' ),
			)
		);
	}
	/**
	 * Helper function to check the existence of a post slug in the database.
	 *
	 * @param string $post_name    Slug of the WordPress Post.
	 *
	 * @since 1.0.0
	 */
	public function the_slug_exists( $post_name ) {
		global $wpdb;
		if ( $wpdb->get_row( 'SELECT post_name FROM wp_posts WHERE post_name = %d', $post_name ) ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Add import and export buttons to the post list for 'wp_block' post type.
	 *
	 * @since 1.0.0
	 */
	public function add_import_export_buttons() {
		if ( get_current_screen()->post_type !== 'wp_block' ) {
			return;
		}
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				const buttonGroup = $('<br /><div class="alignleft actions"></div>');
				const importButton = $('<button type="button" class="button">Import Patterns</button> ');
				const exportButton = $('<button type="button" class="button">Export Patterns</button>');

				importButton.on('click', function() {
					if (confirm('Are you sure you want to import patterns from patterns.json?')) {
						$.post(
							PatternManagerAjax.ajax_url,
							{
								action: 'import_patterns',
								nonce: PatternManagerAjax.nonce
							},
							function(response) {
								if (response.success) {
									alert('Patterns imported successfully');
									location.reload();
								} else {
									alert('Error: ' + response.data);
								}
							}
						);
					}
				});

				exportButton.on('click', function() {
					if (confirm('Are you sure you want to export patterns to patterns.json?')) {
						$.post(
							PatternManagerAjax.ajax_url,
							{
								action: 'export_patterns',
								nonce: PatternManagerAjax.nonce
							},
							function(response) {
								if (response.success) {
									alert('Patterns exported successfully');
								} else {
									alert('Error: ' + response.data);
								}
							}
						);
					}
				});

				buttonGroup.append(importButton);
				buttonGroup.append(exportButton);
				$('.wp-list-table').after(buttonGroup);
			});
		</script>
		<?php
	}

	/**
	 * Import patterns from the patterns.json file.
	 *
	 * @since 1.0.0
	 */
	public function import_patterns() {
		check_ajax_referer( 'pattern_manager_nonce', 'nonce' );

		$file = get_template_directory() . '/patterns.json';
		global $wp_filesystem;
		if ( ! WP_Filesystem() ) {
			wp_send_json_error( 'Filesystem initialization error' );
		}

		if ( ! $wp_filesystem->exists( $file ) ) {
			wp_send_json_error( 'File not found' );
		}

		$patterns_json = $wp_filesystem->get_contents( $file );
		$patterns      = json_decode( $patterns_json, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			wp_send_json_error( 'Invalid JSON format' );
		}

		foreach ( $patterns as $pattern ) {
			if ( ! isset( $pattern['title'] ) ) {
				continue;
			}

			$pattern_assembly = array(
				'post_title'   => wp_strip_all_tags( $pattern['title'] ),
				'post_content' => $pattern['content'],
				'post_status'  => $pattern['status'],
				'post_author'  => 1,
				'post_type'    => 'wp_block',
			);

			if ( ! $this->the_slug_exists( $pattern['slug'] ) || 'draft' === $pattern['status'] ) {
				$new_post_id = wp_insert_post( $pattern_assembly );
				wp_set_object_terms( $new_post_id, $pattern['categories'], 'wp_pattern_category' );
			} else {
				$pattern_assembly['ID'] = url_to_postid( $pattern['slug'] );
				$new_post_id            = wp_update_post( $pattern_assembly );
				wp_set_object_terms( $new_post_id, $pattern['categories'], 'wp_pattern_category' );
			}
		}

		wp_send_json_success( 'Patterns imported successfully' );
	}

	/**
	 * Export patterns to the patterns.json file.
	 *
	 * @since 1.0.0
	 */
	public function export_patterns() {
		check_ajax_referer( 'pattern_manager_nonce', 'nonce' );

		$args     = array(
			'post_type' => 'wp_block',
		);
		$patterns = new WP_Query( $args );

		$patterns_array = array();

		if ( $patterns->have_posts() ) {
			while ( $patterns->have_posts() ) {
				global $post;
				$patterns->the_post();

				$categories       = get_the_terms( $post->id, 'wp_pattern_category' );
				$categories_array = array();

				foreach ( $categories as $category ) :
					$categories_array[] = $category->name;
				endforeach;

				$patterns_array[] = array(
					'title'      => wp_kses_post( get_the_title() ),
					'slug'       => wp_kses_post( $post->post_name ),
					'categories' => $categories_array,
					'content'    => wp_kses_post( get_the_content() ),
					'status'     => wp_kses_post( get_post_status() ),
				);
			}
		} else {
			wp_send_json_error( 'No Patterns Registered' );
			end;
		}

		wp_reset_postdata();

		$file = get_template_directory() . '/patterns.json';
		global $wp_filesystem;
		if ( ! WP_Filesystem() ) {
			wp_send_json_error( 'Filesystem initialization error' );
		}

		$result = $wp_filesystem->put_contents( $file, wp_json_encode( $patterns_array, JSON_PRETTY_PRINT ) );

		if ( ! $result ) {
			wp_send_json_error( 'Error writing to file' );
		}

		wp_send_json_success( 'Patterns exported successfully' );
	}
}

new WdsbtThemePatternManager();

/**
 * End of Theme Pattern Manager class.
 *
 * @since 1.0.0
 * @package YourTheme
 * @subpackage WdsbtThemePatternManager
 */
