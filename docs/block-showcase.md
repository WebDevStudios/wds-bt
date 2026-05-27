# Block Showcase

Admin-only development tool to discover, preview, and inspect all registered blocks (core, theme, third-party) with their attributes.

## How to Create a Showcase Page

1. **Pages → Add New** in WordPress admin.
2. Set **Template** to **Block Showcase** (visible only to administrators).
3. Publish. The page uses the `[wdsbt_block_showcase]` shortcode.

## What It Shows

Blocks grouped by category; for each block: name, identifier, attributes (with types/defaults/options), preset CSS variables when the attribute is set in `theme.json` under `styles.blocks`, and a live preview. Content comes from block `example`, defaults, or generated markup. Some blocks (e.g. `core/legacy-widget`, `core/freeform`) are skipped.

**Block styles** from `block.json` (`styles`, e.g. Separator “Wide Line” / “Dots”) are listed first with a **Style** label; each non-default style gets a preview by merging `is-style-{name}` into the block’s saved markup (`attrs.className` plus the root element in `innerHTML` / `innerContent` when needed) so static blocks like `core/social-links` actually render the correct layout classes.

**Variations** from `WP_Block_Type::get_variations()` (PHP / `block.json` `variations`) appear with a **Variation** label. Variations registered only in JavaScript (`registerBlockVariation`) are not visible to PHP and do not appear here.

## Security

Template and shortcode are restricted to `manage_options`. Non-admins visiting a Block Showcase template get a 404.

## Styling

SCSS: `assets/scss/templates/block-showcase.scss`. Classes: `.wdsbt-block-showcase`, `.wdsbt-showcase-section-heading`, `.wdsbt-showcase-category`, `.wdsbt-showcase-block-card`, etc.

## Technical Notes

Uses WordPress 6.9+ streaming block parser and `WP_Block_Processor`; output is KSES-sanitized. Data URIs allowed for images in previews during rendering.
