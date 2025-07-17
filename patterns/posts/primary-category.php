<?php
/**
 * Title: Primary Category
 * Slug: wdsbt/primary-category
 * Categories: posts
 * Block Types: custom/primary-category
 * Inserter: false
 *
 * @package wdsbt
 */

$wdsbt_category = get_the_category();

if ( $wdsbt_category ) {
	// Initialize variables.
	$wdsbt_category_display = '';
	$wdsbt_category_link    = '';

	// Get primary category if available.
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$wdsbt_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
		$wdsbt_primary_term = $wdsbt_primary_term->get_primary_term();
		$wdsbt_term         = get_term( $wdsbt_primary_term );

		// Check if primary term exists.
		if ( ! is_wp_error( $wdsbt_term ) ) {
			$wdsbt_category_display = $wdsbt_term->name;
			$wdsbt_category_link    = get_category_link( $wdsbt_term->term_id );
		}
	}

	// If primary term not found, use the first category.
	if ( empty( $wdsbt_category_display ) && isset( $category[0] ) ) {
		$wdsbt_category_display = $category[0]->name;
		$wdsbt_category_link    = get_category_link( $category[0]->term_id );
	}

	// Display category if available.
	if ( ! empty( $wdsbt_category_display ) ) {
		?>
		<h2 class="wp-block-heading has-large-font-size" style="padding-top: var(--wp--preset--spacing--20); padding-bottom: var(--wp--preset--spacing--20);">
			More <a href="<?php echo esc_url( $wdsbt_category_link ); ?>"><?php echo esc_html( $wdsbt_category_display ); ?></a>
		</h2>
		<?php
	}
}
