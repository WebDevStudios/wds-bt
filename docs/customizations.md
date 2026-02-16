# Customizations

## Block Styles

- **Register:** Add entries in `inc/hooks/register-block-styles.php` (name + label).
- **Core overrides:** Add SCSS in `assets/scss/blocks/core/` with filename matching block name. Auto-enqueued; do not import into main `index.scss`. Then `npm run build`.
- **Third-party overrides:** Add SCSS in `assets/scss/blocks/third-party/` named by block slug (e.g. `test.scss` for `wdsbt/test`). Auto-enqueued; do not import. Then `npm run build`.

## Block Variations

1. Create a JS file in `assets/js/block-variations/`.
2. Import it in `assets/js/block-variations/index.js`.
3. Use `wp.blocks.registerBlockVariation('core/paragraph', { name, title, attributes, ... })`. Block names: check `wp.blocks.getBlockTypes()` in the editor console.

## Unregister Blocks / Variations

Edit `assets/js/editor.js`: set `unusedBlocks`, `unregisterBlockVariations`, and `keepEmbeds` (for core/embed). Unused blocks and variations are unregistered; only listed embed variations are kept.

## Mixins

- **Responsive:** `assets/scss/abstracts/responsive-mixins.scss` — `@include responsive-mixins.responsive-min(600px)`, `responsive-max(600px)`, `responsive-range(600px, 1200px)`.
- **Mobile only:** `assets/scss/abstracts/mobile-only-mixins.scss` — `@include mobile-only-mixins.mobile-only` to hide on desktop and show on mobile (a11y-friendly).
