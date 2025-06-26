<?php
/**
 * Title: Default header
 * Slug: wdsbt/header
 * Categories: header
 * Block Types: core/template-part/header
 *
 * @package wdsbt
 */

// Determine whether to display site logo or site title.
$wds_site_info = has_custom_logo() ? '<!-- wp:site-logo {"width":150,"shouldSyncIcon":true,"style":{"layout":{"selfStretch":"fit","flexSize":null}}} /-->' : '<!-- wp:site-title /-->';
?>

<!-- wp:group {"className":"alignfull header-container","layout":{"type":"constrained"}} -->
<div id="top" class="wp-block-group alignfull header-container"><!-- wp:group {"className":"header-container-grid","align":"wide","layout":{"type":"grid","columnCount":"2","minimumColumnWidth":null}} -->
<div class="wp-block-group header-container-grid">
<?php echo wp_kses_post( $wds_site_info ); ?>

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"templateLock":false,"lock":{"move":false,"remove":false},"metadata":{"name":"Primary Menu"},"style":{"layout":{"selfStretch":"fill","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:navigation {"layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right","flexWrap":"nowrap","orientation":"horizontal"}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
