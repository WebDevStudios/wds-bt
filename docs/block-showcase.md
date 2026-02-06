# Block Showcase

Admin-only development tool to discover, preview, and inspect all registered blocks (core, theme, third-party) with their attributes.

## How to Create a Showcase Page

1. **Pages → Add New** in WordPress admin.
2. Set **Template** to **Block Showcase** (visible only to administrators).
3. Publish. The page uses the `[wdsbt_block_showcase]` shortcode.

## What It Shows

Blocks grouped by category; for each block: name, identifier, attributes (with types/defaults/options), and a live preview. Content comes from block `example`, defaults, or generated markup. Some blocks (e.g. `core/legacy-widget`, `core/freeform`) are skipped.

## Security

Template and shortcode are restricted to `manage_options`. Non-admins visiting a Block Showcase template get a 404.

## Styling

SCSS: `assets/scss/templates/block-showcase.scss`. Classes: `.wdsbt-block-showcase`, `.wdsbt-showcase-section-heading`, `.wdsbt-showcase-category`, `.wdsbt-showcase-block-card`, etc.

## Technical Notes

Uses WordPress 6.9+ streaming block parser and `WP_Block_Processor`; output is KSES-sanitized. Data URIs allowed for images in previews during rendering.
