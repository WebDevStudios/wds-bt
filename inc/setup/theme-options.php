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
			</div>
		</div>
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
