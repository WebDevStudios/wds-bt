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

	$skip_blocks = array(
		'core/legacy-widget',
		'core/freeform',
	);

	$organized = array(
		'core'  => array(),
		'wdsbt' => array(),
	);

	foreach ( $all_blocks as $block_name => $block_type ) {
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
			if ( ! isset( $organized[ $namespace ] ) ) {
				$organized[ $namespace ] = array();
			}
			$organized[ $namespace ][ $block_name ] = $block_type;
		}
	}

	return $organized;
}

/**
 * Convert block example data to block markup.
 *
 * Similar to WordPress's getBlockFromExample() in JavaScript.
 * Handles both simple attribute examples and full example objects with innerBlocks.
 *
 * @param string $block_name The fully qualified block name.
 * @param array  $example    The example data (can be just attributes or full example object).
 * @return string Block markup.
 */
function get_block_from_example( $block_name, $example ) {
	if ( ! is_array( $example ) || empty( $example ) ) {
		return '';
	}

	$attributes = array();
	if ( isset( $example['attributes'] ) && is_array( $example['attributes'] ) ) {
		$attributes = $example['attributes'];
	} elseif ( ! isset( $example['innerBlocks'] ) && ! isset( $example['innerContent'] ) ) {
		$attributes = $example;
	}

	if ( isset( $attributes['style'] ) ) {
		unset( $attributes['style'] );
	}

	$attributes_json = ! empty( $attributes ) ? wp_json_encode( $attributes ) : '';

	$inner_content = '';
	if ( isset( $example['innerBlocks'] ) && is_array( $example['innerBlocks'] ) && ! empty( $example['innerBlocks'] ) ) {
		foreach ( $example['innerBlocks'] as $inner_block ) {
			if ( ! isset( $inner_block['name'] ) ) {
				continue;
			}
			$inner_example = array();
			if ( isset( $inner_block['attributes'] ) ) {
				$inner_example['attributes'] = $inner_block['attributes'];
			}
			if ( isset( $inner_block['innerBlocks'] ) ) {
				$inner_example['innerBlocks'] = $inner_block['innerBlocks'];
			}
			$inner_content .= get_block_from_example( $inner_block['name'], $inner_example );
		}
	}

	$block_markup = sprintf( '<!-- wp:%s', $block_name );
	if ( ! empty( $attributes_json ) ) {
		$block_markup .= ' ' . $attributes_json;
	}
	$block_markup .= ' -->';

	if ( ! empty( $inner_content ) ) {
		$block_markup .= $inner_content;
	} elseif ( isset( $example['innerContent'] ) && is_array( $example['innerContent'] ) ) {
		$block_markup .= implode( '', $example['innerContent'] );
	}

	$block_markup .= sprintf( '<!-- /wp:%s -->', $block_name );

	return $block_markup;
}

/**
 * Get default block content for rendering in showcase.
 *
 * @param string $block_name The fully qualified block name (e.g., 'core/paragraph').
 * @param object $block_type The block type object.
 * @return string Default block HTML content.
 */
function get_block_showcase_content( $block_name, $block_type ) {
	if ( isset( $block_type->example ) && ! empty( $block_type->example ) ) {
		$example_markup = get_block_from_example( $block_name, $block_type->example );
		if ( ! empty( $example_markup ) ) {
			return $example_markup;
		}
	}

	$core_defaults = array(
		'core/paragraph'       => '<!-- wp:paragraph --><p>This is a paragraph block with <strong>formatted text</strong> and <em>emphasis</em>.</p><!-- /wp:paragraph -->',
		'core/heading'         => '<!-- wp:heading {"level":1} --><h1 class="wp-block-heading">Heading H1</h1><!-- /wp:heading --><!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Heading H2</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3 class="wp-block-heading">Heading H3</h3><!-- /wp:heading --><!-- wp:heading {"level":4} --><h4 class="wp-block-heading">Heading H4</h4><!-- /wp:heading --><!-- wp:heading {"level":5} --><h5 class="wp-block-heading">Heading H5</h5><!-- /wp:heading --><!-- wp:heading {"level":6} --><h6 class="wp-block-heading">Heading H6</h6><!-- /wp:heading -->',
		'core/list'            => '<!-- wp:list {"type":"decimal"} --><ul class="wp-block-list"><!-- wp:list-item --><li>These words these are these these example are words example these example.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Example words are example are these are example are these.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Words these example are words are these words example are these example words.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Example are example are example these words these example words.</li><!-- /wp:list-item --></ul><!-- /wp:list -->',
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
		'core/image'           => '<!-- wp:image {"sizeSlug":"medium"} --><figure class="wp-block-image size-medium"><img src="https://placehold.co/600x400/orange/white" alt="Placeholder"/></figure><!-- /wp:image -->',
		'core/gallery'         => '<!-- wp:gallery {"linkTo":"lightbox","sizeSlug":"full"} --><figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="https://placehold.co/400x600" alt=""/></figure><!-- /wp:image --><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="https://placehold.co/600x100" alt=""/></figure><!-- /wp:image --><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="https://placehold.co/350x510" alt=""/></figure><!-- /wp:image --><!-- wp:image {"lightbox":{"enabled":true},"sizeSlug":"full","linkDestination":"none"} --><figure class="wp-block-image size-full"><img src="https://placehold.co/1200x400" alt=""/></figure><!-- /wp:image --></figure><!-- /wp:gallery -->',
		'core/audio'           => '<!-- wp:audio --><figure class="wp-block-audio"><audio controls src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3"></audio></figure><!-- /wp:audio -->',
		'core/accordion'       => '<!-- wp:accordion --><div role="group" class="wp-block-accordion"><!-- wp:accordion-item --><div class="wp-block-accordion-item"><!-- wp:accordion-heading --><h3 class="wp-block-accordion-heading"><button class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Accordion Title 1</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3><!-- /wp:accordion-heading --><!-- wp:accordion-panel --><div role="region" class="wp-block-accordion-panel"><!-- wp:paragraph --><p>Words these example words these example are example these example are words. These example these example words example words are words are these example these words these. Example words example are example these example are example. Example words these are these words example these words are these words. Words these are example these example these are.</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>Are these example are example these are words example are words are. Example are these example words example are these are example. Example are words these are words are words these are. These are example these example these words are words are. Are example these example words example are example words are these words these.</p><!-- /wp:paragraph --></div><!-- /wp:accordion-panel --></div><!-- /wp:accordion-item --><!-- wp:accordion-item --><div class="wp-block-accordion-item"><!-- wp:accordion-heading --><h3 class="wp-block-accordion-heading"><button class="wp-block-accordion-heading__toggle"><span class="wp-block-accordion-heading__toggle-title">Accordion Title 2</span><span class="wp-block-accordion-heading__toggle-icon" aria-hidden="true">+</span></button></h3><!-- /wp:accordion-heading --><!-- wp:accordion-panel --><div role="region" class="wp-block-accordion-panel"><!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>Are words these words example words are these are these words.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Are these example are these words these words example these example are these are these.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Example are these example words these example are these words.</li><!-- /wp:list-item --><!-- wp:list-item --><li>Are words example are words example these example these example words example.</li><!-- /wp:list-item --></ul><!-- /wp:list --></div><!-- /wp:accordion-panel --></div><!-- /wp:accordion-item --></div><!-- /wp:accordion -->',
		'core/cover'           => '<!-- wp:cover {"overlayColor":"accent-1","isUserOverlayColor":true,"isDark":false,"layout":{"type":"constrained"}} --><div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-accent-1-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","fontSize":"large","fitText":true} --><h2 class="wp-block-heading has-text-align-center has-fit-text has-large-font-size">Cover Block</h2><!-- /wp:heading --></div></div><!-- /wp:cover -->',
		'core/file'            => '<!-- wp:file {"href":"https://example.com/sample.pdf","showDownloadButton":true} --><div class="wp-block-file"><a href="https://example.com/sample.pdf" class="wp-block-file__button" download>Download</a> <a href="https://example.com/sample.pdf">sample.pdf</a></div><!-- /wp:file -->',
		'core/media-text'      => '<!-- wp:media-text {"mediaType":"image","mediaWidth":50} --><div class="wp-block-media-text alignwide is-stacked-on-mobile" style="grid-template-columns:50% auto"><figure class="wp-block-media-text__media"><img src="https://placehold.co/600x400/000000/FFF" alt="Media & Text"/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph --><p>Media &amp; Text Block. Example these example are words are words are example are example these. Are these example these words example are these words these example. Words are example words these are example words. These are words are words example are example words are words are words these. Are words example words are example words these example these are example.</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text -->',
		'core/video'           => '<!-- wp:video --><figure class="wp-block-video"><video controls src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"></video><figcaption class="wp-element-caption">Video Caption</figcaption></figure><!-- /wp:video -->',
		'core/buttons'         => '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Fill Button</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Outline Button</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-minimal"} --><div class="wp-block-button is-style-minimal"><a class="wp-block-button__link wp-element-button">Minimal Button</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-text"} --><div class="wp-block-button is-style-text"><a class="wp-block-button__link wp-element-button">Text Only Button</a></div><!-- /wp:button --></div><!-- /wp:buttons -->',
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
		'core/latest-posts'    => '<!-- wp:latest-posts {"displayPostContent":true,"excerptLength":25,"displayAuthor":true,"displayPostDate":true,"postLayout":"grid","displayFeaturedImage":true,"featuredImageAlign":"center","featuredImageSizeSlug":"large","addLinkToFeaturedImage":true} /-->',
		'core/page-list'       => '<!-- wp:page-list --><ul class="wp-block-page-list"><li class="wp-block-pages-list__item"><a href="#">Page 1</a></li><li class="wp-block-pages-list__item"><a href="#">Page 2</a></li></ul><!-- /wp:page-list -->',
		'core/search'          => '<!-- wp:search {"label":"Button Outside","width":75,"widthUnit":"%","buttonText":"Search"} /--><!-- wp:search {"label":"Button Inside","buttonText":"Search","buttonPosition":"button-inside"} /--><!-- wp:search {"label":"No Button","buttonText":"Search","buttonPosition":"no-button","buttonUseIcon":true} /--><!-- wp:search {"label":"Button Only","buttonText":"Search","buttonPosition":"button-only","isSearchFieldHidden":true} /--><!-- wp:search {"label":"Button Icon Inside","buttonText":"Search","buttonPosition":"button-inside","buttonUseIcon":true} /-->',
		'core/social-links'    => '<!-- wp:social-links {"iconColor":"foreground","size":"has-small-icon-size"} --><ul class="wp-block-social-links has-icon-color has-small-icon-size"><!-- wp:social-link {"url":"https://facebook.com","service":"facebook"} /--><!-- wp:social-link {"url":"https://twitter.com","service":"twitter"} /--></ul><!-- /wp:social-links -->',
		'core/tag-cloud'       => '<!-- wp:tag-cloud {"numberOfTags":5} --><p class="wp-block-tag-cloud"><a href="#" class="tag-cloud-link">Tag 1</a> <a href="#" class="tag-cloud-link">Tag 2</a> <a href="#" class="tag-cloud-link">Tag 3</a></p><!-- /wp:tag-cloud -->',
		'core/site-logo'       => '<!-- wp:site-logo {"width":100} /-->',
		'core/site-title'      => '<!-- wp:site-title /-->',
		'core/site-tagline'    => '<!-- wp:site-tagline /-->',
		'core/embed'           => '<!-- wp:embed {"url":"https://www.youtube.com/watch?v=dQw4w9WgXcQ"} /-->',
	);

	if ( isset( $core_defaults[ $block_name ] ) ) {
		return $core_defaults[ $block_name ];
	}

	if ( isset( $block_type->attributes ) && is_array( $block_type->attributes ) ) {
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

	$skip_blocks = array( 'core/legacy-widget', 'core/freeform' );
	if ( in_array( $block_name, $skip_blocks, true ) ) {
		return '<p><em>This block type cannot be previewed in the showcase.</em></p>';
	}

	if ( 'core/embed' === $block_name ) {
		$blocks = parse_blocks( $block_content );
		if ( ! empty( $blocks ) && ! empty( $blocks[0] ) && ! empty( $blocks[0]['attrs']['url'] ) ) {
			$url           = $blocks[0]['attrs']['url'];
			$video_id      = null;
			$provider_slug = '';
			$type          = 'video';

			if ( preg_match( '/youtube\.com\/watch\?v=([^&]+)/', $url, $matches ) ) {
				$video_id      = $matches[1];
				$provider_slug = 'youtube';
			} elseif ( preg_match( '/youtu\.be\/([^?]+)/', $url, $matches ) ) {
				$video_id      = $matches[1];
				$provider_slug = 'youtube';
			}

			if ( 'youtube' === $provider_slug && $video_id ) {
				$embed_url   = 'https://www.youtube.com/embed/' . esc_attr( $video_id );
				$oembed_html = sprintf(
					'<iframe loading="lazy" title="%s" width="600" height="338" src="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>',
					esc_attr__( 'Embedded content from YouTube', 'wdsbt' ),
					esc_url( $embed_url )
				);

				$classes = array(
					'wp-block-embed',
					'is-type-' . $type,
					'is-provider-' . $provider_slug,
					'wp-block-embed-' . $provider_slug,
					'wp-embed-aspect-16-9',
					'wp-has-aspect-ratio',
				);

				$rendered = sprintf(
					'<figure class="%s"><div class="wp-block-embed__wrapper">%s</div></figure>',
					esc_attr( implode( ' ', $classes ) ),
					$oembed_html
				);

				return $rendered;
			}

			$oembed_html = false;
			$provider    = wp_oembed_get_provider( $url );

			if ( $provider ) {
				$oembed_html = wp_oembed_get(
					$url,
					array(
						'width'  => 600,
						'height' => 400,
					)
				);
			}

			if ( $oembed_html ) {
				if ( false !== strpos( $url, 'vimeo.com' ) ) {
					$provider_slug = 'vimeo';
				} elseif ( false !== strpos( $url, 'twitter.com' ) || false !== strpos( $url, 'x.com' ) ) {
					$provider_slug = 'twitter';
					$type          = 'rich';
				} elseif ( false !== strpos( $url, 'instagram.com' ) ) {
					$provider_slug = 'instagram';
					$type          = 'rich';
				}

				$classes = array(
					'wp-block-embed',
					'is-type-' . $type,
				);
				if ( $provider_slug ) {
					$classes[] = 'is-provider-' . $provider_slug;
					$classes[] = 'wp-block-embed-' . $provider_slug;
				}
				$classes[] = 'wp-embed-aspect-16-9';
				$classes[] = 'wp-has-aspect-ratio';

				$rendered = sprintf(
					'<figure class="%s"><div class="wp-block-embed__wrapper">%s</div></figure>',
					esc_attr( implode( ' ', $classes ) ),
					$oembed_html
				);

				return $rendered;
			} else {
				// Fallback if oEmbed fetch fails - show URL.
				return '<div class="wp-block-embed"><p><em>Embed preview not available. URL: <a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $url ) . '</a></em></p></div>';
			}
		}
	}

	$rendered = do_blocks( $block_content );

	return $rendered;
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
 * Get formatted block attributes for display.
 *
 * @param object $block_type The block type object.
 * @return array Array of formatted attribute information.
 */
function get_block_attributes_info( $block_type ) {
	if ( ! isset( $block_type->attributes ) || ! is_array( $block_type->attributes ) || empty( $block_type->attributes ) ) {
		return array();
	}

	$attributes_info = array();
	foreach ( $block_type->attributes as $attr_name => $attr_config ) {
		$info = array(
			'name'    => $attr_name,
			'type'    => isset( $attr_config['type'] ) ? $attr_config['type'] : 'unknown',
			'default' => isset( $attr_config['default'] ) ? $attr_config['default'] : null,
		);

		if ( isset( $attr_config['enum'] ) ) {
			$info['enum'] = $attr_config['enum'];
		}
		if ( isset( $attr_config['source'] ) ) {
			$info['source'] = $attr_config['source'];
		}

		$attributes_info[ $attr_name ] = $info;
	}

	return $attributes_info;
}

/**
 * Get block category for organization using WordPress's native categorization.
 *
 * @param string $block_name The fully qualified block name.
 * @param object $block_type The block type object.
 * @return string Category slug.
 */
function get_block_category( $block_name, $block_type = null ) {
	if ( null === $block_type ) {
		$block_registry = \WP_Block_Type_Registry::get_instance();
		$block_type     = $block_registry->get_registered( $block_name );
	}

	if ( $block_type && isset( $block_type->category ) && ! empty( $block_type->category ) ) {
		return $block_type->category;
	}

	if ( strpos( $block_name, 'wdsbt/' ) === 0 ) {
		return 'wdsbt';
	}

	return 'other';
}
