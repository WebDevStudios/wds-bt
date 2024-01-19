<?php
/**
 * Title: Default header
 * Slug: powder/header-dark
 * Categories: header
 * Block Types: core/template-part/header
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"0px"},"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"black","layout":{"inherit":true,"type":"constrained"},"metadata":{"name":"Header Dark"}} -->
<div class="wp-block-group alignfull has-black-background-color has-background" style="margin-top:0px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between","flexWrap":"nowrap"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:site-logo {"width":150,"style":{"layout":{"selfStretch":"fixed","flexSize":"25%"}}} /-->

		<!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"75%"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"},"metadata":{"name":"Primary Menu"}} -->
		<div class="wp-block-group">
			<!-- wp:navigation {"ref":7,"layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"right","flexWrap":"wrap"}} /-->
			<!-- wp:search {"label":"Search","showLabel":false,"buttonText":"Search","buttonPosition":"button-only","buttonUseIcon":true,"isSearchFieldHidden":true,"className":"mobile-search"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->

