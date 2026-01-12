<?php
/**
 * WDS-BT Theme Options admin page.
 *
 * Adds a settings page under Tools with options for speculative loading behavior.
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Registers the WDS-BT settings page under Tools.
 */
function register_settings_page() {
	add_submenu_page(
		'tools.php',
		__( 'WDSBT Settings', 'wdsbt' ),
		__( 'WDSBT Settings', 'wdsbt' ),
		'manage_options',
		'wdsbt-settings',
		__NAMESPACE__ . '\render_settings_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_settings_page' );

/**
 * Renders the settings page UI.
 */
function render_settings_page() {
	$exclude_option = 'exclude_sensitive_pages';
	$global_option  = 'maybe_disable_speculative_loading';
	$debug_option   = 'maybe_log_speculative_debug';

	$exclude_value = get_option( $exclude_option, true );
	$global_value  = get_option( $global_option, false );
	$debug_value   = get_option( $debug_option, false );

	// Save on POST.
	if ( isset( $_POST['settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['settings_nonce'] ) ), 'save_settings' ) ) {
		$exclude_value = isset( $_POST[ $exclude_option ] );
		$global_value  = isset( $_POST[ $global_option ] );
		$debug_value   = isset( $_POST[ $debug_option ] );

		update_option( $exclude_option, $exclude_value );
		update_option( $global_option, $global_value );
		update_option( $debug_option, $debug_value );

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Settings saved.', 'wdsbt' ) . '</p></div>';
	}

	// Handle flush pattern registry request.
	if ( isset( $_POST['wdsbt_flush_patterns'] ) && check_admin_referer( 'wdsbt_flush_patterns_action' ) ) {
		wdsbt_flush_pattern_registry();
		// Clear object cache.
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
		// Delete all transients.
		global $wpdb;
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_%'" );
		echo '<div class="notice notice-success is-dismissible"><p>Block pattern registry, Object cache and transients flushed.</p>';
		echo '<p style="margin-top:1em;"><strong>Note:</strong> Due to WordPress caching, <u>new pattern categories and patterns may only appear after switching to another theme and back, or waiting for the cache to expire</u>. This is a WordPress limitation and does not affect production sites where patterns/categories are set before launch.</p></div>';
		// Inject JS to notify the block editor.
		echo "<script>if (window.localStorage) { localStorage.setItem('wdsbt_patterns_flushed', Date.now()); }</script>";
	}

	// Handle WebP regeneration request.
	if ( isset( $_POST['wdsbt_regenerate_webp'] ) && check_admin_referer( 'wdsbt_regenerate_webp_action' ) ) {
		// Ensure webp-uploads.php is loaded before checking.
		$webp_file = get_template_directory() . '/inc/performance/webp-uploads.php';
		if ( file_exists( $webp_file ) && ! function_exists( __NAMESPACE__ . '\\webp_supported' ) ) {
			require_once $webp_file;
		}

		if ( ! function_exists( __NAMESPACE__ . '\\webp_supported' ) || ! webp_supported() ) {
			echo '<div class="notice notice-error is-dismissible"><p>WebP generation is not supported on this server. Please install Imagick with WebP support or GD library with imagewebp() function.</p></div>';
		} else {
			$attachments = get_posts(
				array(
					'post_type'      => 'attachment',
					'post_mime_type' => array( 'image/jpeg', 'image/png' ),
					'posts_per_page' => -1,
					'fields'         => 'ids',
				)
			);

			if ( empty( $attachments ) ) {
				echo '<div class="notice notice-warning is-dismissible"><p>No JPEG or PNG images found.</p></div>';
			} else {
				$count     = 0;
				$processed = 0;

				foreach ( $attachments as $attachment_id ) {
					if ( function_exists( __NAMESPACE__ . '\\regenerate_webp_for_attachment' ) ) {
						if ( regenerate_webp_for_attachment( $attachment_id ) ) {
							++$processed;
						}
					}
					++$count;
				}

				echo '<div class="notice notice-success is-dismissible"><p>';
				printf(
					// translators: %1$d: number of processed images, %2$d: total images.
					esc_html__( 'Regenerated WebP for %1$d of %2$d images.', 'wdsbt' ),
					$processed,
					$count
				);
				echo '</p></div>';
			}
		}
	}

	// Determine current status summary.
	$loading_status = $global_value
		? __( 'Speculative Loading is currently <strong>disabled globally</strong>.', 'wdsbt' )
		: ( $exclude_value
			? __( 'Speculative Loading is <strong>enabled</strong>, but <strong>sensitive pages are excluded</strong>.', 'wdsbt' )
			: __( 'Speculative Loading is <strong>fully enabled</strong>.', 'wdsbt' )
		);
	?>

	<div class="wrap">
		<h1><?php esc_html_e( 'WDSBT Settings', 'wdsbt' ); ?></h1>
		<h2><?php esc_html_e( 'Control optional features for this block theme.', 'wdsbt' ); ?></h2>

		<div class="notice notice-info" style="margin-top: 20px;">
			<p><?php echo wp_kses_post( $loading_status ); ?></p>
		</div>

		<div class="options-container" style="display: inline-flex; gap: 20px; width: 100%;">

			<div class="card">
				<h2 class="title"><?php esc_html_e( 'Performance Options', 'wdsbt' ); ?></h2>

				<form method="post" style="margin-top: 2em;">
					<?php wp_nonce_field( 'save_settings', 'settings_nonce' ); ?>

					<label style="display: flex; align-items: center; gap: 12px;">
						<input type="checkbox" name="<?php echo esc_attr( $global_option ); ?>" <?php checked( $global_value ); ?> />
						<h3><?php esc_html_e( 'Disable Speculative Loading for the Entire Site', 'wdsbt' ); ?></h3>
					</label>
					<p class="description">
						<?php esc_html_e( 'No pages will be prefetched or prerendered.', 'wdsbt' ); ?>
					</p>

					<label style="display: flex; align-items: center; gap: 12px; margin-top: 16px;">
						<input type="checkbox" name="<?php echo esc_attr( $exclude_option ); ?>" <?php checked( $exclude_value ); ?> />
						<h3><?php esc_html_e( 'Exclude sensitive pages only (e.g., Cart, Checkout)', 'wdsbt' ); ?></h3>
					</label>
					<p class="description">
						<?php esc_html_e( 'Recommended for e-commerce and membership sites.', 'wdsbt' ); ?>
					</p>

					<label style="display: flex; align-items: center; gap: 12px; margin-top: 16px;">
						<input type="checkbox" name="<?php echo esc_attr( $debug_option ); ?>" <?php checked( $debug_value ); ?> />
						<h3><?php esc_html_e( 'Enable debug logging for speculative loading in console', 'wdsbt' ); ?></h3>
					</label>
					<p class="description">
						<?php esc_html_e( 'Logs whether a page was prerendered, is prerendering, or was loaded normally.', 'wdsbt' ); ?>
					</p>

					<?php submit_button( __( 'Save Settings', 'wdsbt' ) ); ?>
				</form>
			</div>

			<div class="card">
				<h2 class="title"><?php esc_html_e( 'Development Tools', 'wdsbt' ); ?></h2>

				<form method="post">
					<?php wp_nonce_field( 'wdsbt_flush_patterns_action' ); ?>
					<p>
						<input type="submit" name="wdsbt_flush_patterns" class="button button-primary" value="<?php esc_attr_e( 'Flush object cache and transients', 'wdsbt' ); ?>">
					</p>
				</form>

				<?php
				// Ensure webp-uploads.php is loaded.
				$webp_file = get_template_directory() . '/inc/performance/webp-uploads.php';
				if ( file_exists( $webp_file ) && ! function_exists( __NAMESPACE__ . '\\webp_supported' ) ) {
					require_once $webp_file;
				}

				// Diagnostic information.
				$gd_loaded        = extension_loaded( 'gd' );
				$gd_info          = function_exists( 'gd_info' ) ? gd_info() : array();
				$imagewebp_exists = function_exists( 'imagewebp' );
				$imagick_loaded   = extension_loaded( 'imagick' );
				$imagick_class    = class_exists( 'Imagick' );
				// Check what webp_supported() actually returns.
				$webp_supported_func_exists = function_exists( __NAMESPACE__ . '\\webp_supported' );
				$webp_supported_result      = false;
				if ( $webp_supported_func_exists ) {
					$webp_supported_result = webp_supported();
				}
				$webp_supported = $webp_supported_result;
				?>

				<div style="margin-top: 20px; padding: 15px; background: #f0f0f1; border-left: 4px solid #2271b1;">
					<h3 style="margin-top: 0;"><?php esc_html_e( 'WebP Support Diagnostics', 'wdsbt' ); ?></h3>
					<ul style="list-style: disc; margin-left: 20px;">
						<li><strong>GD Extension:</strong> <span style="color: <?php echo $gd_loaded ? '#00a32a' : '#d63638'; ?>;"><?php echo $gd_loaded ? 'Loaded' : 'Not loaded'; ?></span></li>
						<?php if ( $gd_loaded && ! empty( $gd_info ) ) : ?>
							<li><strong>GD WebP Support:</strong> <span style="color: <?php echo isset( $gd_info['WebP Support'] ) && $gd_info['WebP Support'] ? '#00a32a' : '#d63638'; ?>;"><?php echo isset( $gd_info['WebP Support'] ) && $gd_info['WebP Support'] ? 'Yes' : 'No'; ?></span></li>
							<li><strong>GD Version:</strong> <?php echo isset( $gd_info['GD Version'] ) ? esc_html( $gd_info['GD Version'] ) : 'Unknown'; ?></li>
						<?php endif; ?>
						<li><strong>imagewebp() function:</strong> <span style="color: <?php echo $imagewebp_exists ? '#00a32a' : '#d63638'; ?>;"><?php echo $imagewebp_exists ? 'Available' : 'Not available'; ?></span></li>
						<li><strong>Imagick Extension:</strong> <span style="color: <?php echo $imagick_loaded ? '#00a32a' : '#d63638'; ?>;"><?php echo $imagick_loaded ? 'Loaded' : 'Not loaded'; ?></span></li>
						<li><strong>Imagick Class:</strong> <span style="color: <?php echo $imagick_class ? '#00a32a' : '#d63638'; ?>;"><?php echo $imagick_class ? 'Available' : 'Not available'; ?></span></li>
						<?php if ( $imagick_loaded && $imagick_class ) : ?>
							<?php
							try {
								$imagick         = new \Imagick();
								$formats         = $imagick->queryFormats();
								$webp_in_formats = in_array( 'WEBP', $formats, true );
								?>
								<li><strong>Imagick WebP Support:</strong> <span style="color: <?php echo $webp_in_formats ? '#00a32a' : '#d63638'; ?>;"><?php echo $webp_in_formats ? 'Yes' : 'No'; ?></span></li>
							<?php } catch ( \Exception $e ) { ?>
								<li><strong>Imagick WebP Support:</strong> <span style="color: #d63638;">Error checking formats</span></li>
							<?php } ?>
						<?php endif; ?>
						<li><strong>webp_supported() function exists:</strong> <span style="color: <?php echo $webp_supported_func_exists ? '#00a32a' : '#d63638'; ?>;"><?php echo $webp_supported_func_exists ? 'Yes' : 'No'; ?></span></li>
						<li><strong>webp_supported() result:</strong> <span style="color: <?php echo $webp_supported_result ? '#00a32a' : '#d63638'; ?>;"><?php echo $webp_supported_result ? 'true' : 'false'; ?></span></li>
						<li><strong>Overall WebP Support:</strong> <span style="color: <?php echo $webp_supported ? '#00a32a' : '#d63638'; ?>;"><strong><?php echo $webp_supported ? 'Available' : 'Not available'; ?></strong></span></li>
					</ul>
				</div>

				<?php if ( $webp_supported ) : ?>
					<form method="post" style="margin-top: 20px;">
						<?php wp_nonce_field( 'wdsbt_regenerate_webp_action' ); ?>
						<p>
							<input type="submit" name="wdsbt_regenerate_webp" class="button button-secondary" value="<?php esc_attr_e( 'Regenerate WebP for All Images', 'wdsbt' ); ?>" onclick="return confirm('<?php esc_attr_e( 'This will regenerate WebP versions for all existing JPEG and PNG images. This may take a while. Continue?', 'wdsbt' ); ?>');">
						</p>
						<p class="description">
							<?php esc_html_e( 'Generate WebP versions for all existing images. This may take several minutes depending on the number of images.', 'wdsbt' ); ?>
						</p>
					</form>
				<?php else : ?>
					<p style="margin-top: 20px; color: #d63638;">
						<strong><?php esc_html_e( 'WebP generation not available:', 'wdsbt' ); ?></strong><br>
						<?php esc_html_e( 'Your server does not support WebP generation. Please install Imagick with WebP support or GD library with imagewebp() function.', 'wdsbt' ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<?php
		// Font Detection Debug at the bottom.
		if ( function_exists( __NAMESPACE__ . '\\get_font_detection_debug_html' ) ) {
			echo get_font_detection_debug_html();
		}
		?>
	</div>
	<?php
}

// Add flush pattern registry function if not present.
if ( ! function_exists( 'wdsbt_flush_pattern_registry' ) ) {

	/**
	 * Flush pattern registry.
	 */
	function wdsbt_flush_pattern_registry() {
		// Unregister all custom patterns (adjust 'wdsbt/' to your theme slug).
		$patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();
		foreach ( $patterns as $pattern ) {
			if ( strpos( $pattern['name'], 'wdsbt/' ) === 0 ) {
				unregister_block_pattern( $pattern['name'] );
			}
		}
		// Re-register categories and patterns.
		if ( function_exists( 'wdsbt_register_dynamic_block_pattern_categories' ) ) {
			wdsbt_register_dynamic_block_pattern_categories();
		}
	}
}
