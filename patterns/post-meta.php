<?php
/**
 * Title: Post Meta
 * Slug: wdsbt/post-meta
 * Categories: content
 * Block Types: custom/post-meta
 *
 * @package wdsbt
 */

// Check if modified date is same as current date.
$wds_post_date = get_the_date( 'U' ) !== get_the_modified_date( 'U' ) ? '<!-- wp:paragraph --><p>Posted On:</p><!-- /wp:paragraph --><!-- wp:post-date {"format":"M j, Y"} /--> <!-- wp:paragraph --><p>Last Updated:</p><!-- /wp:paragraph --><!-- wp:post-date {"format":"M j, Y","displayType":"modified"} /-->' : '<!-- wp:paragraph --><p>Posted On:</p><!-- /wp:paragraph --><!-- wp:post-date {"format":"M j, Y"} /-->';

?>

<!-- wp:group {"metadata":{"name":"Post Meta"},"className":"post-meta","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group post-meta">
	<?php echo wp_kses_post( $wds_post_date ); ?>
</div>
<!-- /wp:group -->

