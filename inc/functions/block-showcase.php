<?php
/**
 * Block Showcase functionality using WP_Block_Processor.
 *
 * Dynamically discovers and displays all registered blocks (core and custom)
 * using the efficient streaming block parser from WordPress 6.9+.
 *
 * @see https://make.wordpress.org/core/2025/11/19/introducing-the-streaming-block-parser-in-wordpress-6-9/
 *
 * @package WDSBT
 */

namespace WebDevStudios\wdsbt;

/**
 * Get the color palette from theme.json (theme-defined colors only, no core defaults).
 *
 * Uses the theme's theme.json so only Base, Primary, Accent, etc. are shown—
 * not WordPress core palette (cyan, blush, gray, etc.).
 *
 * @return array List of color entries with 'slug', 'name', 'color' keys.
 */
function get_theme_json_color_palette() {
	if ( ! class_exists( 'WP_Theme_JSON_Resolver' ) ) {
		return array();
	}
	$theme_json = \WP_Theme_JSON_Resolver::get_theme_data();
	if ( ! $theme_json ) {
		return array();
	}
	$settings = $theme_json->get_settings();
	if ( empty( $settings['color']['palette'] ) || ! is_array( $settings['color']['palette'] ) ) {
		return array();
	}
	$raw = $settings['color']['palette'];

	// Theme data may expose palette as flat or as [ 'theme' => [ ... ] ].
	$list  = array();
	$first = reset( $raw );
	if ( is_array( $first ) && isset( $first['color'] ) ) {
		$list = $raw;
	} elseif ( isset( $raw['theme'] ) && is_array( $raw['theme'] ) ) {
		$list = $raw['theme'];
	} else {
		foreach ( $raw as $origin_palette ) {
			if ( is_array( $origin_palette ) ) {
				$list = array_merge( $list, array_values( $origin_palette ) );
			}
		}
	}

	return is_array( $list ) ? array_values( $list ) : array();
}

/**
 * Brightness 0–255 for #rgb / #rrggbb (for preview label contrast).
 *
 * @param string $hex Hex color with or without #.
 * @return int|null Brightness or null if not parseable.
 */
function showcase_color_hex_brightness( $hex ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( strlen( $hex ) === 3 && ctype_xdigit( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( strlen( $hex ) !== 6 || ! ctype_xdigit( $hex ) ) {
		return null;
	}
	$r = hexdec( substr( $hex, 0, 2 ) );
	$g = hexdec( substr( $hex, 2, 2 ) );
	$b = hexdec( substr( $hex, 4, 2 ) );

	return (int) round( ( $r * 299 + $g * 587 + $b * 114 ) / 1000 );
}

/**
 * Foreground hex for text on a CSS color (hex only; else dark default).
 *
 * @param string $css_color theme.json color value.
 * @return string #191919 or #ffffff.
 */
function showcase_color_contrast_foreground( $css_color ) {
	if ( preg_match( '/#([0-9a-f]{3}|[0-9a-f]{6})\b/i', (string) $css_color, $m ) ) {
		$bright = showcase_color_hex_brightness( '#' . $m[1] );
		if ( null !== $bright ) {
			return $bright > 140 ? '#191919' : '#ffffff';
		}
	}

	return '#191919';
}

/**
 * Human-readable color sample for the showcase (hex if possible).
 *
 * @param string $css_color theme.json color value.
 * @return string Display string.
 */
function showcase_color_display_value( $css_color ) {
	$s = trim( (string) $css_color );
	if ( preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $s, $m ) ) {
		if ( strlen( $m[1] ) === 3 ) {
			return strtoupper(
				'#' . $m[1][0] . $m[1][0] . $m[1][1] . $m[1][1] . $m[1][2] . $m[1][2]
			);
		}

		return strtoupper( '#' . $m[1] );
	}

	if ( strlen( $s ) > 48 ) {
		return substr( $s, 0, 45 ) . '…';
	}

	return $s;
}

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
		'core/cover'           => '<!-- wp:cover {"overlayColor":"primary-50","isUserOverlayColor":true,"isDark":false,"layout":{"type":"constrained"}} --><div class="wp-block-cover is-light"><span aria-hidden="true" class="wp-block-cover__background has-primary-50-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","fontSize":"large","fitText":true} --><h2 class="wp-block-heading has-text-align-center has-fit-text has-large-font-size">Cover Block</h2><!-- /wp:heading --></div></div><!-- /wp:cover -->',
		'core/file'            => '<!-- wp:file {"href":"https://example.com/sample.pdf","showDownloadButton":true} --><div class="wp-block-file"><a href="https://example.com/sample.pdf" class="wp-block-file__button" download>Download</a> <a href="https://example.com/sample.pdf">sample.pdf</a></div><!-- /wp:file -->',
		'core/media-text'      => '<!-- wp:media-text {"mediaType":"image","mediaWidth":50} --><div class="wp-block-media-text alignwide is-stacked-on-mobile" style="grid-template-columns:50% auto"><figure class="wp-block-media-text__media"><img src="https://placehold.co/600x400/000000/FFF" alt="Media & Text"/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph --><p>Media &amp; Text Block. Example these example are words are words are example are example these. Are these example these words example are these words these example. Words are example words these are example words. These are words are words example are example words are words are words these. Are words example words are example words these example these are example.</p><!-- /wp:paragraph --></div></div><!-- /wp:media-text -->',
		'core/video'           => '<!-- wp:video --><figure class="wp-block-video"><video controls src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"></video><figcaption class="wp-element-caption">Video Caption</figcaption></figure><!-- /wp:video -->',
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
		'core/rss'             => '<!-- wp:rss {"feedURL":"https://wordpress.org/news/feed/","itemsToShow":5} /-->',
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
 * Merges variation attributes into the first matching block in a parsed tree.
 *
 * @param array[] $blocks      Parsed blocks (by reference).
 * @param string  $block_name  Block name to match (e.g. core/button).
 * @param array   $attributes  Attributes to merge into attrs.
 * @return bool True if a block was updated.
 */
function apply_variation_attributes_to_block_tree( array &$blocks, $block_name, array $attributes ) {
	foreach ( $blocks as &$block ) {
		if ( ! empty( $block['blockName'] ) && $block['blockName'] === $block_name ) {
			$block['attrs'] = array_merge( (array) ( $block['attrs'] ?? array() ), $attributes );
			return true;
		}
		if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			if ( apply_variation_attributes_to_block_tree( $block['innerBlocks'], $block_name, $attributes ) ) {
				return true;
			}
		}
	}
	unset( $block );
	return false;
}

/**
 * Default root CSS class for a block (matches saved markup, e.g. wp-block-social-links).
 *
 * @param string $block_name Block name (core/social-links, wdsbt/foo).
 * @return string Class substring to locate in inner HTML, or empty.
 */
function get_showcase_block_root_css_class( $block_name ) {
	$block_name = (string) $block_name;
	if ( '' === $block_name || false === strpos( $block_name, '/' ) ) {
		return '';
	}
	if ( 0 === strpos( $block_name, 'core/' ) ) {
		return str_replace( 'core/', 'wp-block-', $block_name );
	}

	return 'wp-block-' . str_replace( '/', '-', $block_name );
}

/**
 * Injects a single is-style-* class on the first tag whose class list contains the block root class.
 * Removes any existing is-style-* classes on that tag so one block style is active.
 *
 * @param string $markup         HTML fragment.
 * @param string $root_css_class Substring such as wp-block-social-links.
 * @param string $style_class    Full class, e.g. is-style-pill-shape (already sanitized).
 * @return string Updated markup.
 */
function showcase_inject_block_style_into_markup( $markup, $root_css_class, $style_class ) {
	if ( ! is_string( $markup ) || '' === $markup || '' === $root_css_class || '' === $style_class ) {
		return $markup;
	}
	if ( false === strpos( $markup, $root_css_class ) ) {
		return $markup;
	}

	$processor = new \WP_HTML_Tag_Processor( $markup );
	while ( $processor->next_tag() ) {
		$class = $processor->get_attribute( 'class' );
		if ( ! is_string( $class ) || false === strpos( $class, $root_css_class ) ) {
			continue;
		}
		$classes = array_filter( preg_split( '/\s+/', $class, -1, PREG_SPLIT_NO_EMPTY ) );
		$classes = array_values(
			array_filter(
				$classes,
				static function ( $token ) {
					return ! preg_match( '/^is-style-/', $token );
				}
			)
		);
		if ( ! in_array( $style_class, $classes, true ) ) {
			$classes[] = $style_class;
		}
		$processor->set_attribute( 'class', implode( ' ', $classes ) );
		break;
	}

	return $processor->get_updated_html();
}

/**
 * Merges className attribute: strip is-style-*, add one block style class.
 *
 * @param array  $attrs       Block attrs.
 * @param string $style_class Full is-style-* class.
 * @return array Updated attrs.
 */
function showcase_merge_block_style_classname_attr( array $attrs, $style_class ) {
	$existing = isset( $attrs['className'] ) ? (string) $attrs['className'] : '';
	$classes  = array_filter( preg_split( '/\s+/', $existing, -1, PREG_SPLIT_NO_EMPTY ) );
	$classes  = array_values(
		array_filter(
			$classes,
			static function ( $token ) {
				return ! preg_match( '/^is-style-/', $token );
			}
		)
	);
	if ( ! in_array( $style_class, $classes, true ) ) {
		$classes[] = $style_class;
	}
	$attrs['className'] = implode( ' ', $classes );
	return $attrs;
}

/**
 * Applies a block style preview to one parsed block (attrs + innerHTML / innerContent).
 *
 * @param array  $block       Parsed block (by reference).
 * @param string $block_name  Expected blockName.
 * @param string $style_class Full sanitized class, e.g. is-style-wide.
 * @return bool True if this block was updated.
 */
function showcase_apply_block_style_to_parsed_block( array &$block, $block_name, $style_class ) {
	if ( empty( $block['blockName'] ) || $block['blockName'] !== $block_name ) {
		return false;
	}

	$root = get_showcase_block_root_css_class( $block_name );
	if ( '' === $root ) {
		return false;
	}

	$block['attrs'] = showcase_merge_block_style_classname_attr( (array) ( $block['attrs'] ?? array() ), $style_class );

	if ( ! empty( $block['innerHTML'] ) && is_string( $block['innerHTML'] ) && false !== strpos( $block['innerHTML'], $root ) ) {
		$block['innerHTML'] = showcase_inject_block_style_into_markup( $block['innerHTML'], $root, $style_class );
	}

	if ( ! empty( $block['innerContent'] ) && is_array( $block['innerContent'] ) ) {
		foreach ( $block['innerContent'] as $i => $chunk ) {
			if ( is_string( $chunk ) && false !== strpos( $chunk, $root ) ) {
				$block['innerContent'][ $i ] = showcase_inject_block_style_into_markup( $chunk, $root, $style_class );
			}
		}
	}

	$has_inner_placeholder = false;
	if ( ! empty( $block['innerContent'] ) && is_array( $block['innerContent'] ) ) {
		foreach ( $block['innerContent'] as $c ) {
			if ( null === $c ) {
				$has_inner_placeholder = true;
				break;
			}
		}
	}
	if ( ! $has_inner_placeholder && isset( $block['innerHTML'] ) && is_string( $block['innerHTML'] ) ) {
		$block['innerContent'] = array( $block['innerHTML'] );
	}

	return true;
}

/**
 * Applies a block style to the first matching block in a parsed tree (depth-first).
 *
 * @param array[] $blocks       Parsed blocks (by reference).
 * @param string  $block_name   Block name to match.
 * @param string  $style_class  Full is-style-* class (sanitized).
 * @return bool True if a block was updated.
 */
function showcase_apply_block_style_in_tree( array &$blocks, $block_name, $style_class ) {
	foreach ( $blocks as &$block ) {
		if ( showcase_apply_block_style_to_parsed_block( $block, $block_name, $style_class ) ) {
			return true;
		}
		if ( ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			if ( showcase_apply_block_style_in_tree( $block['innerBlocks'], $block_name, $style_class ) ) {
				return true;
			}
		}
	}
	unset( $block );
	return false;
}

/**
 * Serialized showcase markup with a block style class applied (block.json `styles`, not variations).
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type  Block type instance.
 * @param string $style_slug  Style `name` from block.json (e.g. wide, dots).
 * @return string Serialized markup or empty string.
 */
function get_showcase_serialized_for_block_style( $block_name, $block_type, $style_slug ) {
	$style_slug = is_string( $style_slug ) ? trim( $style_slug ) : '';
	if ( '' === $style_slug ) {
		return '';
	}

	$base = get_block_showcase_content( $block_name, $block_type );
	if ( '' === $base ) {
		return '';
	}

	$slug_sanitized = sanitize_html_class( str_replace( '/', '-', $style_slug ) );
	if ( '' === $slug_sanitized ) {
		return '';
	}
	$class_segment = 'is-style-' . $slug_sanitized;

	$blocks = parse_blocks( $base );
	if ( empty( $blocks ) || ! showcase_apply_block_style_in_tree( $blocks, $block_name, $class_segment ) ) {
		return '';
	}

	return serialize_blocks( $blocks );
}

/**
 * Rendered previews for each non-default block style (block.json `styles`).
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type  Block type instance.
 * @return array<int, array{kind: string, name: string, title: string, html: string}>
 */
function get_block_showcase_style_previews( $block_name, $block_type ) {
	if ( ! isset( $block_type->styles ) || ! is_array( $block_type->styles ) || empty( $block_type->styles ) ) {
		return array();
	}

	$base = get_block_showcase_content( $block_name, $block_type );
	$out  = array();

	foreach ( $block_type->styles as $style ) {
		if ( ! is_array( $style ) || empty( $style['name'] ) || ! is_string( $style['name'] ) ) {
			continue;
		}
		if ( ! empty( $style['isDefault'] ) ) {
			continue;
		}

		$slug       = $style['name'];
		$serialized = get_showcase_serialized_for_block_style( $block_name, $block_type, $slug );
		if ( '' === $serialized || $serialized === $base ) {
			continue;
		}

		$html = render_block_for_showcase( $block_name, $block_type, $serialized );
		if ( '' === trim( $html ) ) {
			continue;
		}

		$title = isset( $style['label'] ) && is_string( $style['label'] ) ? $style['label'] : $slug;

		$out[] = array(
			'kind'  => 'style',
			'name'  => $slug,
			'title' => $title,
			'html'  => $html,
		);
	}

	return $out;
}

/**
 * Merges style previews (first) and variation previews for the showcase UI.
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type  Block type instance.
 * @return array<int, array{kind: string, name: string, title: string, html: string}>
 */
function get_block_showcase_style_and_variation_previews( $block_name, $block_type ) {
	return array_merge(
		get_block_showcase_style_previews( $block_name, $block_type ),
		get_block_showcase_variation_previews( $block_name, $block_type )
	);
}

/**
 * Builds serialized block markup for a registered variation (PHP / block.json only).
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type  WP_Block_Type instance.
 * @param array  $variation   Variation definition (name, title, attributes, innerBlocks, …).
 * @return string Serialized markup or empty string if nothing applicable.
 */
function get_showcase_serialized_for_variation( $block_name, $block_type, array $variation ) {
	$base = get_block_showcase_content( $block_name, $block_type );
	if ( '' === $base ) {
		return '';
	}

	$has_inner = ! empty( $variation['innerBlocks'] ) && is_array( $variation['innerBlocks'] );
	$has_attrs = isset( $variation['attributes'] ) && is_array( $variation['attributes'] );

	if ( $has_inner ) {
		$example = array( 'innerBlocks' => $variation['innerBlocks'] );
		if ( $has_attrs ) {
			$example['attributes'] = $variation['attributes'];
		}
		$built = get_block_from_example( $block_name, $example );
		if ( '' !== $built ) {
			return $built;
		}
	}

	if ( $has_attrs && ! empty( $variation['attributes'] ) ) {
		$blocks = parse_blocks( $base );
		if ( ! empty( $blocks ) && apply_variation_attributes_to_block_tree( $blocks, $block_name, $variation['attributes'] ) ) {
			return serialize_blocks( $blocks );
		}
		$built = get_block_from_example( $block_name, array( 'attributes' => $variation['attributes'] ) );
		if ( '' !== $built ) {
			return $built;
		}
	}

	return '';
}

/**
 * Human-readable title for a block variation in the showcase.
 *
 * @param array $variation Variation definition.
 * @return string
 */
function get_block_showcase_variation_title( array $variation ) {
	if ( ! empty( $variation['title'] ) && is_string( $variation['title'] ) ) {
		return $variation['title'];
	}
	if ( ! empty( $variation['name'] ) && is_string( $variation['name'] ) ) {
		return $variation['name'];
	}
	return '';
}

/**
 * Returns rendered previews for each server-registered variation that differs from the default showcase.
 *
 * Variations registered only in JavaScript are not available to PHP and are omitted.
 *
 * @param string $block_name  Fully qualified block name.
 * @param object $block_type Block type object.
 * @return array<int, array{kind: string, name: string, title: string, html: string}>
 */
function get_block_showcase_variation_previews( $block_name, $block_type ) {
	if ( ! $block_type instanceof \WP_Block_Type || ! method_exists( $block_type, 'get_variations' ) ) {
		return array();
	}

	$variations = $block_type->get_variations();
	if ( empty( $variations ) || ! is_array( $variations ) ) {
		return array();
	}

	$base = get_block_showcase_content( $block_name, $block_type );
	$out  = array();

	foreach ( $variations as $variation ) {
		if ( ! is_array( $variation ) || empty( $variation['name'] ) || ! is_string( $variation['name'] ) ) {
			continue;
		}

		$serialized = get_showcase_serialized_for_variation( $block_name, $block_type, $variation );
		if ( '' === $serialized || $serialized === $base ) {
			continue;
		}

		$html = render_block_for_showcase( $block_name, $block_type, $serialized );
		if ( '' === trim( $html ) ) {
			continue;
		}

		$title = get_block_showcase_variation_title( $variation );
		if ( '' === $title ) {
			$title = $variation['name'];
		}

		$out[] = array(
			'kind'  => 'variation',
			'name'  => $variation['name'],
			'title' => $title,
			'html'  => $html,
		);
	}

	return $out;
}

/**
 * Render a block for the showcase using WP_Block_Processor.
 *
 * @param string      $block_name    The fully qualified block name.
 * @param object      $block_type    The block type object.
 * @param string|null $block_content Optional serialized block markup; default uses showcase sample content.
 * @return string Rendered block HTML.
 */
function render_block_for_showcase( $block_name, $block_type, $block_content = null ) {
	if ( null === $block_content ) {
		$block_content = get_block_showcase_content( $block_name, $block_type );
	}

	if ( empty( $block_content ) ) {
		return '';
	}

	$skip_blocks = array(
		'core/legacy-widget',
		'core/freeform',
		// WPML parses saved block HTML for current vs other language nodes; showcase uses minimal markup only.
		'wpml/language-switcher',
		'wpml/navigation-language-switcher',
	);
	if ( in_array( $block_name, $skip_blocks, true ) ) {
		if ( 'wpml/language-switcher' === $block_name || 'wpml/navigation-language-switcher' === $block_name ) {
			return '<p class="wdsbt-showcase-block-unavailable"><em>' . esc_html__( 'WPML language switcher blocks need full saved markup from the editor and cannot be previewed here.', 'wdsbt' ) . '</em></p>';
		}
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

	if ( 'core/rss' === $block_name ) {
		$blocks = parse_blocks( $block_content );
		if ( ! empty( $blocks ) && ! empty( $blocks[0] ) ) {
			$feed_url = isset( $blocks[0]['attrs']['feedURL'] ) ? $blocks[0]['attrs']['feedURL'] : '';

			if ( empty( $feed_url ) || ! filter_var( $feed_url, FILTER_VALIDATE_URL ) ) {
				$block_content = '<!-- wp:rss {"feedURL":"https://wordpress.org/news/feed/","itemsToShow":5} /-->';
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
 * HTML id for deep-linking to a block card on the showcase page (URL fragment).
 *
 * @param string $block_name The fully qualified block name (e.g. core/paragraph).
 * @return string Safe unique id, e.g. block-showcase-core-paragraph.
 */
function get_block_showcase_anchor_id( $block_name ) {
	$slug = str_replace( '/', '-', (string) $block_name );
	$slug = preg_replace( '/[^a-z0-9\-]+/i', '-', $slug );
	$slug = trim( preg_replace( '/-+/', '-', strtolower( $slug ) ), '-' );
	if ( '' === $slug ) {
		$slug = 'block-' . substr( md5( $block_name ), 0, 8 );
	}
	return 'block-showcase-' . $slug;
}

/**
 * Maps block attribute names to theme.json style paths (per block in styles.blocks).
 *
 * @return array<string, array<int, array<int, string>>>
 */
function get_block_attribute_theme_json_paths() {
	return array(
		'backgroundColor' => array(
			array( 'color', 'background' ),
		),
		'textColor'       => array(
			array( 'color', 'text' ),
			array( 'hover', 'color', 'text' ),
		),
		'borderColor'     => array(
			array( 'border', 'color' ),
			array( 'color', 'border' ),
		),
		'gradient'        => array(
			array( 'color', 'gradient' ),
		),
		'fontFamily'      => array(
			array( 'typography', 'fontFamily' ),
		),
		'fontSize'        => array(
			array( 'typography', 'fontSize' ),
		),
	);
}

/**
 * Reads a nested value from an array using a path of keys.
 *
 * @param array             $data Source array.
 * @param array<int,string> $path Key path.
 * @return mixed|null
 */
function get_array_path_value( array $data, array $path ) {
	$current = $data;
	foreach ( $path as $key ) {
		if ( ! is_array( $current ) || ! array_key_exists( $key, $current ) ) {
			return null;
		}
		$current = $current[ $key ];
	}
	return $current;
}

/**
 * Converts a theme.json preset reference to a CSS custom property.
 *
 * @param mixed $value theme.json value (e.g. var:preset|color|accent-1).
 * @return string|null CSS var() string or null if not a preset reference.
 */
function theme_json_preset_ref_to_css_var( $value ) {
	if ( ! is_string( $value ) ) {
		return null;
	}
	$value = trim( $value );
	if ( preg_match( '/^var:preset\|([a-z0-9-]+)\|([a-z0-9-]+)$/i', $value, $matches ) ) {
		return 'var(--wp--preset--' . $matches[1] . '--' . $matches[2] . ')';
	}
	if ( 0 === strpos( $value, 'var(--wp--preset--' ) ) {
		return $value;
	}
	return null;
}

/**
 * Preset CSS variables from theme.json block styles keyed by block attribute name.
 *
 * @param string $block_name Fully qualified block name (e.g. core/heading).
 * @return array<string, string> Attribute name => CSS variable.
 */
function get_block_theme_json_attribute_variables( $block_name ) {
	if ( ! class_exists( 'WP_Theme_JSON_Resolver' ) || '' === (string) $block_name ) {
		return array();
	}

	$theme_json = \WP_Theme_JSON_Resolver::get_theme_data();
	if ( ! $theme_json || ! method_exists( $theme_json, 'get_data' ) ) {
		return array();
	}

	$data = $theme_json->get_data();
	if ( empty( $data['styles']['blocks'][ $block_name ] ) || ! is_array( $data['styles']['blocks'][ $block_name ] ) ) {
		return array();
	}

	$block_styles = $data['styles']['blocks'][ $block_name ];
	$variables    = array();

	foreach ( get_block_attribute_theme_json_paths() as $attr_name => $paths ) {
		foreach ( $paths as $path ) {
			$value = get_array_path_value( $block_styles, $path );
			$css   = theme_json_preset_ref_to_css_var( $value );
			if ( null !== $css ) {
				$variables[ $attr_name ] = $css;
				break;
			}
		}
	}

	return $variables;
}

/**
 * Get formatted block attributes for display.
 *
 * @param object      $block_type  The block type object.
 * @param string|null $block_name  Fully qualified block name for theme.json variable lookup.
 * @return array Array of formatted attribute information.
 */
function get_block_attributes_info( $block_type, $block_name = null ) {
	if ( ! isset( $block_type->attributes ) || ! is_array( $block_type->attributes ) || empty( $block_type->attributes ) ) {
		return array();
	}

	$theme_variables = array();
	if ( is_string( $block_name ) && '' !== $block_name ) {
		$theme_variables = get_block_theme_json_attribute_variables( $block_name );
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
		if ( isset( $theme_variables[ $attr_name ] ) ) {
			$info['variable'] = $theme_variables[ $attr_name ];
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
