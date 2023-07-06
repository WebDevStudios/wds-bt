<?php
/**
 * Title: Default 404
 * Slug: powder/404
 * Description: 404 page with page not found message.
 * Categories: hidden
 * Inserter: false
 */
?>
<!-- wp:heading {"level":1} -->
<h1><?php echo esc_html__( 'Not found, error 404', 'powder' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Oops, the page you are looking for does not exist or is no longer available. Please use the search form below to find your way.', 'powder' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:search {"width":80,"widthUnit":"%","showLabel":false,"buttonText":"Search"} /-->
