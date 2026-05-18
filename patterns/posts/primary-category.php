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

$wdsbt_category         = get_the_category();
$wdsbt_category_display = '';
$wdsbt_category_link    = '';

if ( $wdsbt_category ) {
	if ( class_exists( 'WPSEO_Primary_Term' ) ) {
		$wdsbt_primary_term = new WPSEO_Primary_Term( 'category', get_the_ID() );
		$wdsbt_primary_id   = $wdsbt_primary_term->get_primary_term();
		if ( $wdsbt_primary_id ) {
			$wdsbt_term = get_term( (int) $wdsbt_primary_id );
			if ( $wdsbt_term instanceof WP_Term && ! is_wp_error( $wdsbt_term ) ) {
				$wdsbt_category_display = $wdsbt_term->name;
				$wdsbt_category_link    = get_category_link( $wdsbt_term->term_id );
			}
		}
	}

	if ( '' === $wdsbt_category_display && isset( $wdsbt_category[0] ) ) {
		$wdsbt_category_display = $wdsbt_category[0]->name;
		$wdsbt_category_link    = get_category_link( $wdsbt_category[0]->term_id );
	}
}

if ( '' !== $wdsbt_category_display && '' !== $wdsbt_category_link ) {
	?>
	<h2 class="wp-block-heading has-large-font-size" style="padding-top: var(--wp--preset--spacing--20); padding-bottom: var(--wp--preset--spacing--20);">
		More <a href="<?php echo esc_url( $wdsbt_category_link ); ?>"><?php echo esc_html( $wdsbt_category_display ); ?></a>
	</h2>
	<?php
} else {
	?>
	<h2 class="wp-block-heading has-large-font-size" style="padding-top: var(--wp--preset--spacing--20); padding-bottom: var(--wp--preset--spacing--20);">
		More <a href="#"><?php echo esc_html__( 'Primary category', 'wdsbt' ); ?></a>
	</h2>
	<?php
}
