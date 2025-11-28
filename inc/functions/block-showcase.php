<?php
/**
 * Block Showcase functionality using WP_Block_Processor.
 *
 * Dynamically discovers and displays all registered blocks (core and custom)
 * using the efficient streaming block parser from WordPress 6.9+.
 *
 * @see https://make.wordpress.org/core/2025/11/19/introducing-the-streaming-block-parser-in-wordpress-6-9/
 *
 * @package wdsbt
 */

namespace WebDevStudios\wdsbt;

/**
 * Get all registered blocks organized by namespace.
 *
 * @return array Array of blocks organized by namespace (core, wdsbt, etc.).
 */
function get_all_registered_blocks() {
	$block_registry = \WP_Block_Type_Registry::get_instance();
	$all_blocks     = $block_registry->get_all_registered();

	// Blocks that cannot be safely rendered in showcase.
	$skip_blocks = array(
		'core/legacy-widget',
		'core/freeform',
		'core/html', // Can contain arbitrary HTML that might break rendering.
	);

	$organized = array(
		'core'  => array(),
		'wdsbt' => array(),
		'other' => array(),
	);

	foreach ( $all_blocks as $block_name => $block_type ) {
		// Skip problematic blocks.
		if ( in_array( $block_name, $skip_blocks, true ) ) {
			continue;
		}

		$parts = explode( '/', $block_name );
		if ( count( $parts ) !== 2 ) {
			continue;
		}

		$namespace = $parts[0];
		$name      = $parts[1];

		if ( 'core' === $namespace ) {
			$organized['core'][ $block_name ] = $block_type;
		} elseif ( 'wdsbt' === $namespace ) {
			$organized['wdsbt'][ $block_name ] = $block_type;
		} else {
			$organized['other'][ $block_name ] = $block_type;
		}
	}

	return $organized;
}

/**
 * Get default block content for rendering in showcase.
 *
 * @param string $block_name The fully qualified block name (e.g., 'core/paragraph').
 * @param object $block_type The block type object.
 * @return string Default block HTML content.
 */
function get_block_showcase_content( $block_name, $block_type ) {
	// Core block defaults.
	$core_defaults = array(
		'core/paragraph'       => '<!-- wp:paragraph --><p>This is a paragraph block with <strong>formatted text</strong> and <em>emphasis</em>.</p><!-- /wp:paragraph -->',
		'core/heading'         => '<!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Heading Block</h3><!-- /wp:heading -->',
		'core/list'            => '<!-- wp:list --><ul class="wp-block-list"><li>List item one</li><li>List item two</li><li>List item three</li></ul><!-- /wp:list -->',
		'core/quote'           => '<!-- wp:quote --><blockquote class="wp-block-quote"><!-- wp:paragraph --><p>This is a quote block for highlighting important statements.</p><!-- /wp:paragraph --><cite>Citation</cite></blockquote><!-- /wp:quote -->',
		'core/code'            => '<!-- wp:code --><pre class="wp-block-code"><code>function example() {
    return \'code\';
}</code></pre><!-- /wp:code -->',
		'core/preformatted'    => '<!-- wp:preformatted --><pre class="wp-block-preformatted">Preformatted text preserves
    whitespace    and
        formatting.</pre><!-- /wp:preformatted -->',
		'core/pullquote'       => '<!-- wp:pullquote --><figure class="wp-block-pullquote"><blockquote><p>This is a pullquote block.</p><cite>Citation</cite></blockquote></figure><!-- /wp:pullquote -->',
		'core/table'           => '<!-- wp:table --><figure class="wp-block-table"><table><thead><tr><th>Header 1</th><th>Header 2</th></tr></thead><tbody><tr><td>Cell 1</td><td>Cell 2</td></tr></tbody></table></figure><!-- /wp:table -->',
		'core/verse'           => '<!-- wp:verse --><pre class="wp-block-verse">This is a verse block,
    perfect for poetry
        and special formatting.</pre><!-- /wp:verse -->',
		'core/image'           => '<!-- wp:image {"sizeSlug":"medium"} --><figure class="wp-block-image size-medium"><img src="https://via.placeholder.com/400x300" alt="Placeholder"/></figure><!-- /wp:image -->',
		'core/gallery'         => '<!-- wp:gallery {"linkTo":"none","columns":3,"sizeSlug":"thumbnail"} --><figure class="wp-block-gallery has-nested-images columns-3 is-cropped"><figure class="wp-block-image size-thumbnail"><img src="https://via.placeholder.com/150" alt="Gallery 1"/></figure><figure class="wp-block-image size-thumbnail"><img src="https://via.placeholder.com/150" alt="Gallery 2"/></figure><figure class="wp-block-image size-thumbnail"><img src="https://via.placeholder.com/150" alt="Gallery 3"/></figure></figure><!-- /wp:gallery -->',
		'core/audio'           => '<!-- wp:audio --><figure class="wp-block-audio"><audio controls src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"></audio></figure><!-- /wp:audio -->',
		'core/cover'           => '<!-- wp:cover {"url":"https://via.placeholder.com/400x300","dimRatio":50,"overlayColor":"black-alpha-50"} --><div class="wp-block-cover has-black-alpha-50-background-color has-background-dim" style="background-image:url(https://via.placeholder.com/400x300)"><span aria-hidden="true" class="wp-block-cover__background has-black-alpha-50-background-color has-background-dim__100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:paragraph {"align":"center","fontSize":"small"} --><p class="has-text-align-center has-small-font-size">Cover Block</p><!-- /wp:paragraph --></div></div><!-- /wp:cover -->',
		'core/file'            => '<!-- wp:file {"href":"https://example.com/sample.pdf","showDownloadButton":true} --><div class="wp-block-file"><a href="https://example.com/sample.pdf" class="wp-block-file__button" download>Download</a> <a href="https://example.com/sample.pdf">sample.pdf</a></div><!-- /wp:file -->',
		'core/media-text'      => '<!-- wp:media-text {"mediaType":"image","mediaWidth":50} --><div class="wp-block-media-text alignwide is-stacked-on-mobile" style="grid-template-columns:50% auto"><figure class="wp-block-media-text__media"><img src="https://via.placeholder.com/300x200" alt="Media & Text"/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph --><p>Media &amp; Text Block</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text -->',
		'core/video'           => '<!-- wp:video --><figure class="wp-block-video"><video controls src="https://sample-videos.com/video123/mp4/720/big_buck_bunny_720p_1mb.mp4"></video></figure><!-- /wp:video -->',
		'core/buttons'         => '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Button</a></div><!-- /wp:button --></div><!-- /wp:buttons -->',
		'core/columns'         => '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Column 1</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph {"fontSize":"small"} --><p class="has-small-font-size">Column 2</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->',
		'core/group'           => '<!-- wp:group {"backgroundColor":"base","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}}} --><div class="wp-block-group has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph --><p>Group Block</p><!-- /wp:paragraph --></div><!-- /wp:group -->',
		'core/separator'       => '<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity has-css-opacity"/><!-- /wp:separator -->',
		'core/spacer'          => '<!-- wp:spacer {"height":"40px"} --><div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->',
		'core/shortcode'       => '<!-- wp:shortcode --><div class="wp-block-shortcode">[shortcode example="value"]</div><!-- /wp:shortcode -->',
		'core/archives'        => '<!-- wp:archives --><div class="wp-block-archives"><ul class="wp-block-archives-list"><li><a href="#">January 2024</a></li><li><a href="#">February 2024</a></li></ul></div><!-- /wp:archives -->',
		'core/calendar'        => '<!-- wp:calendar --><div class="wp-block-calendar"><table class="wp-calendar-table"><caption>January 2024</caption><thead><tr><th scope="col" title="Monday">M</th><th scope="col" title="Tuesday">T</th><th scope="col" title="Wednesday">W</th><th scope="col" title="Thursday">T</th><th scope="col" title="Friday">F</th><th scope="col" title="Saturday">S</th><th scope="col" title="Sunday">S</th></tr></thead><tbody><tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr></tbody></table></div><!-- /wp:calendar -->',
		'core/categories'      => '<!-- wp:categories --><div class="wp-block-categories"><ul class="wp-block-categories-list"><li><a href="#">Category 1</a></li><li><a href="#">Category 2</a></li></ul></div><!-- /wp:categories -->',
		'core/html'            => '<!-- wp:html --><div class="wp-block-html"><p>Custom HTML block</p></div><!-- /wp:html -->',
		'core/latest-comments' => '<!-- wp:latest-comments --><div class="wp-block-latest-comments"><!-- wp:latest-comments /--></div><!-- /wp:latest-comments -->',
		'core/latest-posts'    => '<!-- wp:latest-posts {"postsToShow":3} --><ul class="wp-block-latest-posts"><li><a href="#">Post Title 1</a></li><li><a href="#">Post Title 2</a></li></ul><!-- /wp:latest-posts -->',
		'core/page-list'       => '<!-- wp:page-list --><ul class="wp-block-page-list"><li class="wp-block-pages-list__item"><a href="#">Page 1</a></li><li class="wp-block-pages-list__item"><a href="#">Page 2</a></li></ul><!-- /wp:page-list -->',
		'core/search'          => '<!-- wp:search {"label":"Search","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} --><div class="wp-block-search__button-inside wp-block-search__icon-button"><label for="wp-block-search__input-sb" class="wp-block-search__label">Search</label><div class="wp-block-search__inside-wrapper "><input type="search" id="wp-block-search__input-sb" class="wp-block-search__input" name="s" value="" placeholder="" required=""/><button type="submit" class="wp-block-search__button has-icon" aria-label="Search"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M13 5c-3.3 0-6 2.7-6 6 0 1.4.5 2.7 1.3 3.7l-3.8 3.8 1.1 1.1 3.8-3.8c1 .8 2.3 1.3 3.7 1.3 3.3 0 6-2.7 6-6S16.3 5 13 5zm0 10.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5z"></path></svg></button></div></div><!-- /wp:search -->',
		'core/social-links'    => '<!-- wp:social-links {"iconColor":"foreground","size":"has-small-icon-size"} --><ul class="wp-block-social-links has-icon-color has-small-icon-size"><!-- wp:social-link {"url":"https://facebook.com","service":"facebook"} /--><!-- wp:social-link {"url":"https://twitter.com","service":"twitter"} /--></ul><!-- /wp:social-links -->',
		'core/tag-cloud'       => '<!-- wp:tag-cloud {"numberOfTags":5} --><p class="wp-block-tag-cloud"><a href="#" class="tag-cloud-link">Tag 1</a> <a href="#" class="tag-cloud-link">Tag 2</a> <a href="#" class="tag-cloud-link">Tag 3</a></p><!-- /wp:tag-cloud -->',
		'core/site-logo'       => '<!-- wp:site-logo {"width":100} /-->',
		'core/site-title'      => '<!-- wp:site-title /-->',
		'core/site-tagline'    => '<!-- wp:site-tagline /-->',
		'core/embed'           => '<!-- wp:embed {"url":"https://www.youtube.com/watch?v=dQw4w9WgXcQ","type":"video","providerNameSlug":"youtube","responsive":true} --><figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">https://www.youtube.com/watch?v=dQw4w9WgXcQ</div></figure><!-- /wp:embed -->',
	);

	if ( isset( $core_defaults[ $block_name ] ) ) {
		return $core_defaults[ $block_name ];
	}

	// For custom blocks, try to get default content from block metadata.
	if ( isset( $block_type->attributes ) && is_array( $block_type->attributes ) ) {
		// Try to create a minimal block with default attributes.
		$attributes = array();
		foreach ( $block_type->attributes as $attr_name => $attr_config ) {
			if ( isset( $attr_config['default'] ) ) {
				$attributes[ $attr_name ] = $attr_config['default'];
			}
		}

		$attributes_json = ! empty( $attributes ) ? wp_json_encode( $attributes ) : '';
		$block_markup    = sprintf(
			'<!-- wp:%s %s /-->',
			$block_name,
			$attributes_json
		);

		return $block_markup;
	}

	// Fallback: create a simple block comment.
	return sprintf( '<!-- wp:%s /-->', $block_name );
}

/**
 * Render a block for the showcase using WP_Block_Processor.
 *
 * @param string $block_name The fully qualified block name.
 * @param object $block_type The block type object.
 * @return string Rendered block HTML.
 */
function render_block_for_showcase( $block_name, $block_type ) {
	$block_content = get_block_showcase_content( $block_name, $block_type );

	if ( empty( $block_content ) ) {
		return '';
	}

	// Additional safety check (blocks should already be filtered, but just in case).
	$skip_blocks = array( 'core/legacy-widget', 'core/freeform' );
	if ( in_array( $block_name, $skip_blocks, true ) ) {
		return '<p><em>This block type cannot be previewed in the showcase.</em></p>';
	}

	// Use WP_Block_Processor to parse and render if available.
	if ( class_exists( 'WP_Block_Processor' ) ) {
		try {
			$processor = new \WP_Block_Processor( $block_content );
			if ( $processor->next_block() ) {
				// Extract the full block HTML.
				$block_html = $processor->extract_full_block_and_advance();
				// Ensure we return a string.
				if ( is_string( $block_html ) ) {
					return $block_html;
				}
			}
		} catch ( Exception $e ) {
			// If processor fails, fall through to fallback method.
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( 'WP_Block_Processor error for ' . $block_name . ': ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		}
	}

	// Fallback: use parse_blocks and render_block.
	$blocks = parse_blocks( $block_content );
	if ( empty( $blocks ) ) {
		return '';
	}

	$output = '';
	foreach ( $blocks as $block ) {
		$rendered = render_block( $block );
		// Ensure render_block returns a string.
		if ( is_string( $rendered ) ) {
			$output .= $rendered;
		} elseif ( is_array( $rendered ) ) {
			// If render_block returns an array, it might be an error or special case.
			// Skip it or convert to string representation.
			continue;
		}
	}

	return $output;
}

/**
 * Get human-readable block name.
 *
 * @param string $block_name The fully qualified block name.
 * @return string Human-readable name.
 */
function get_block_display_name( $block_name ) {
	$parts = explode( '/', $block_name );
	$name  = end( $parts );
	$name  = str_replace( array( '-', '_' ), ' ', $name );
	return ucwords( $name );
}

/**
 * Get block category for organization.
 *
 * @param string $block_name The fully qualified block name.
 * @return string Category name.
 */
function get_block_category( $block_name ) {
	// Core block categories.
	$core_categories = array(
		'text'    => array( 'paragraph', 'heading', 'list', 'quote', 'code', 'preformatted', 'pullquote', 'table', 'verse' ),
		'media'   => array( 'image', 'gallery', 'audio', 'cover', 'file', 'media-text', 'video' ),
		'design'  => array( 'buttons', 'columns', 'group', 'separator', 'spacer' ),
		'widgets' => array( 'shortcode', 'archives', 'calendar', 'categories', 'html', 'latest-comments', 'latest-posts', 'page-list', 'search', 'social-links', 'tag-cloud' ),
		'theme'   => array( 'site-logo', 'site-title', 'site-tagline', 'query', 'post-title', 'post-content', 'post-excerpt', 'post-featured-image', 'post-date', 'post-author', 'post-categories', 'post-tags', 'loginout', 'comments' ),
		'embeds'  => array( 'embed' ),
	);

	$parts = explode( '/', $block_name );
	$name  = end( $parts );

	foreach ( $core_categories as $category => $block_names ) {
		if ( in_array( $name, $block_names, true ) ) {
			return $category;
		}
	}

	// Custom blocks go in their namespace category.
	if ( strpos( $block_name, 'wdsbt/' ) === 0 ) {
		return 'wdsbt';
	}

	return 'other';
}
