<?php
/**
 * Title: Search Results
 * Slug: wdsbt/search-results
 * Categories: search
 * Template Types: search
 * Block Types: custom/search-results
 * Inserter: false
 *
 * @package wdsbt
 */

?>
	<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true}} -->
	<div class="wp-block-query">
		<!-- wp:post-template {"layout":{"type":"default","columnCount":3}} -->
			<!-- wp:post-title {"isLink":true} /-->

			<!-- wp:pattern {"slug":"wdsbt/post-meta"} /-->

			<!-- wp:post-excerpt {"moreText":"Read More"} /-->

			<!-- wp:separator {"className":"is-style-wide"} -->
			<hr class="wp-block-separator has-alpha-channel-opacity is-style-wide"/>
			<!-- /wp:separator -->
		<!-- /wp:post-template -->

		<!-- wp:query-pagination -->
			<!-- wp:query-pagination-previous /-->
			<!-- wp:query-pagination-numbers /-->
			<!-- wp:query-pagination-next /-->
		<!-- /wp:query-pagination -->

		<!-- wp:query-no-results -->
			<!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
			<p>Hmmm... We didn't find anything for your search query.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph -->
			<p>Double check your search for any typos or spelling error or try a different search term.</p>
			<!-- /wp:paragraph -->
		<!-- /wp:query-no-results -->
	</div>
	<!-- /wp:query -->
