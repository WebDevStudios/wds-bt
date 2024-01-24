<?php
/**
 * Settings for the Powder WordPress theme.
 *
 * @package	Powder
 * @author	Brian Gardner
 * @license	GNU General Public License v3
 * @link	https://briangardner.com/
 */
 
/**
 * Add theme settings admin menu.
 */
function powder_theme_menu() {

	add_menu_page(
		'Theme Settings', // Page title
		'Powder', // Menu title
		'manage_options', // Capability
		'powder', // Menu slug
		'powder_theme_settings', // Function
		'dashicons-marker', // Icon URL
		55 // Position
	);

}
add_action('admin_menu', 'powder_theme_menu');

/**
 * Display content for theme settings page.
 */
function powder_theme_settings() {

	if (!current_user_can('manage_options')) {
		wp_die('You do not have sufficient permissions to access this page.');
	}

	?>
	<div class="wrap">
	<h1><?php echo esc_html__( 'Theme Settings', 'powder' ); ?></h1>
	<p><?php echo esc_html__( 'Enable these patterns to display in the Site Editor and Block Inserter.', 'powder' ); ?></p>
	<form method="post" action="options.php">
		<?php settings_fields('powder-theme-settings-group'); ?>
		<?php do_settings_sections('powder-theme-settings-group'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Content', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_1" value="1" <?php checked(1, get_option('powder_setting_option_1', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Examples', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_2" value="1" <?php checked(1, get_option('powder_setting_option_2', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Footers', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_3" value="1" <?php checked(1, get_option('powder_setting_option_3', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Headers', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_4" value="1" <?php checked(1, get_option('powder_setting_option_4', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Hero', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_5" value="1" <?php checked(1, get_option('powder_setting_option_5', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Posts', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_6" value="1" <?php checked(1, get_option('powder_setting_option_6', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Pricing', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_7" value="1" <?php checked(1, get_option('powder_setting_option_7', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Templates', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_8" value="1" <?php checked(1, get_option('powder_setting_option_8', '1'), true); ?>/>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__( 'Testimonials', 'powder' ); ?></th>
				<td>
					<input type="checkbox" name="powder_setting_option_9" value="1" <?php checked(1, get_option('powder_setting_option_9', '1'), true); ?>/>
				</td>
			</tr>
		</table>
		
		<?php submit_button(); ?>
	</form>
	</div>
<?php
}

/**
 * Register and define the settings with sanitization callback.
 */
function powder_theme_admin_init(){

	for ($i = 1; $i <= 9; $i++) {
		register_setting('powder-theme-settings-group', 'powder_setting_option_' . $i, 'sanitize_powder_theme_option');
	}

}
add_action('admin_init', 'powder_theme_admin_init');

/**
 * Sanitize callback function.
 */
function sanitize_powder_theme_option($input) {

	return $input === '1' ? '1' : '0';

}

/**
 * Unregister patterns based on settings.
 */
add_action('init', 'conditional_powder_unregister_patterns');

function conditional_powder_unregister_patterns() {

	if (get_option('powder_setting_option_1', '1') !== '1') {
		powder_unregister_content_patterns();
	}
	if (get_option('powder_setting_option_2', '1') !== '1') {
		powder_unregister_example_patterns();
	}
	if (get_option('powder_setting_option_3', '1') !== '1') {
		powder_unregister_footer_patterns();
	}
	if (get_option('powder_setting_option_4', '1') !== '1') {
		powder_unregister_header_patterns();
	}
	if (get_option('powder_setting_option_5', '1') !== '1') {
		powder_unregister_hero_patterns();
	}
	if (get_option('powder_setting_option_6', '1') !== '1') {
		powder_unregister_posts_patterns();
	}
	if (get_option('powder_setting_option_7', '1') !== '1') {
		powder_unregister_pricing_patterns();
	}
	if (get_option('powder_setting_option_8', '1') !== '1') {
		powder_unregister_template_patterns();
	}
	if (get_option('powder_setting_option_9', '1') !== '1') {
		powder_unregister_testimonials_patterns();
	}

}

/**
 * Unregister Content patterns.
 */
function powder_unregister_content_patterns() {

	unregister_block_pattern( 'powder/content-about-split-dark' );
	unregister_block_pattern( 'powder/content-about-split' );
	unregister_block_pattern( 'powder/content-cta-stacked-dark' );
	unregister_block_pattern( 'powder/content-cta-stacked' );
	unregister_block_pattern( 'powder/content-ebook-covers-dark' );
	unregister_block_pattern( 'powder/content-ebook-covers' );
	unregister_block_pattern( 'powder/content-faqs-columns-dark' );
	unregister_block_pattern( 'powder/content-faqs-columns' );
	unregister_block_pattern( 'powder/content-feature-boxes-dark' );
	unregister_block_pattern( 'powder/content-feature-boxes' );
	unregister_block_pattern( 'powder/content-feature-columns-dark' );
	unregister_block_pattern( 'powder/content-feature-columns' );
	unregister_block_pattern( 'powder/content-link-dark' );
	unregister_block_pattern( 'powder/content-link' );
	unregister_block_pattern( 'powder/content-logos-dark' );
	unregister_block_pattern( 'powder/content-logos' );
	unregister_block_pattern( 'powder/content-progress-bars-dark' );
	unregister_block_pattern( 'powder/content-progress-bars' );
	unregister_block_pattern( 'powder/content-social-numbers-dark' );
	unregister_block_pattern( 'powder/content-social-numbers' );

}

/**
 * Unregister Example patterns.
 */
function powder_unregister_example_patterns() {

	unregister_block_pattern( 'powder/example-business' );
	unregister_block_pattern( 'powder/example-social' );

}

/**
 * Unregister Footer patterns.
 */
function powder_unregister_footer_patterns() {

	unregister_block_pattern( 'powder/footer-featured-dark' );
	unregister_block_pattern( 'powder/footer-featured' );
	unregister_block_pattern( 'powder/footer-mega-dark' );
	unregister_block_pattern( 'powder/footer-mega' );
	unregister_block_pattern( 'powder/footer-small-business-dark' );
	unregister_block_pattern( 'powder/footer-small-business' );
	unregister_block_pattern( 'powder/footer-split-dark' );
	unregister_block_pattern( 'powder/footer-split' );
	unregister_block_pattern( 'powder/footer-stacked-dark' );
	unregister_block_pattern( 'powder/footer-stacked' );

}

/**
 * Unregister Header patterns.
 */
function powder_unregister_header_patterns() {

	unregister_block_pattern( 'powder/header-logo-button-dark' );
	unregister_block_pattern( 'powder/header-logo-button' );
	unregister_block_pattern( 'powder/header-logo-center-dark' );
	unregister_block_pattern( 'powder/header-logo-center' );
	unregister_block_pattern( 'powder/header-logo-social-dark' );
	unregister_block_pattern( 'powder/header-logo-social' );
	unregister_block_pattern( 'powder/header-title-separator-dark' );
	unregister_block_pattern( 'powder/header-title-separator' );

}

/**
 * Unregister Hero patterns.
 */
function powder_unregister_hero_patterns() {

	unregister_block_pattern( 'powder/hero-columns-image-text-dark' );
	unregister_block_pattern( 'powder/hero-columns-image-text' );
	unregister_block_pattern( 'powder/hero-columns-text-image-dark' );
	unregister_block_pattern( 'powder/hero-columns-text-image' );
	unregister_block_pattern( 'powder/hero-cover-text-buttons-dark' );
	unregister_block_pattern( 'powder/hero-cover-text-buttons' );
	unregister_block_pattern( 'powder/hero-image-text-buttons-dark' );
	unregister_block_pattern( 'powder/hero-image-text-buttons' );
	unregister_block_pattern( 'powder/hero-stacked-text-image-dark' );
	unregister_block_pattern( 'powder/hero-stacked-text-image' );
	unregister_block_pattern( 'powder/hero-text-button-dark' );
	unregister_block_pattern( 'powder/hero-text-button' );

}

/**
 * Unregister Posts patterns.
 */
function powder_unregister_posts_patterns() {

	unregister_block_pattern( 'powder/posts-grid' );
	unregister_block_pattern( 'powder/posts-list' );

}

/**
 * Unregister Pricing patterns.
 */
function powder_unregister_pricing_patterns() {

	unregister_block_pattern( 'powder/pricing-2-columns' );
	unregister_block_pattern( 'powder/pricing-2-columns-dark' );
	unregister_block_pattern( 'powder/pricing-3-columns' );
	unregister_block_pattern( 'powder/pricing-3-columns-dark' );
	unregister_block_pattern( 'powder/pricing-4-columns' );
	unregister_block_pattern( 'powder/pricing-4-columns-dark' );

}

/**
 * Unregister Template patterns.
 */
function powder_unregister_template_patterns() {

	unregister_block_pattern( 'powder/template-page-box-shadow' );
	unregister_block_pattern( 'powder/template-page-gradient-cover' );
	unregister_block_pattern( 'powder/template-single-box-shadow' );
	unregister_block_pattern( 'powder/template-single-gradient-cover' );

}

/**
 * Unregister Testimonials patterns.
 */
function powder_unregister_testimonials_patterns() {

	unregister_block_pattern( 'powder/testimonials-columns-dark' );
	unregister_block_pattern( 'powder/testimonials-columns' );
	unregister_block_pattern( 'powder/testimonials-grid-dark' );
	unregister_block_pattern( 'powder/testimonials-grid' );
	unregister_block_pattern( 'powder/testimonials-single-dark' );
	unregister_block_pattern( 'powder/testimonials-single' );

}
