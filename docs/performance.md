# Performance & Images

## Template-Specific Styles

CSS in `build/css/templates/` is enqueued only when the request matches: reserved (`404`, search, archive), CPT by name, or page template slug. Add SCSS under `assets/scss/templates/`, run `npm run build`; no PHP config needed.

## Block Styles

Core overrides in `assets/scss/blocks/core/` are per-block via `wp_enqueue_block_style()`. Third-party overrides in `assets/scss/blocks/third-party/` load only on singular content that uses those blocks. Core block assets stay separate (`should_load_separate_core_block_assets`).

## Cache Versioning

All theme-built CSS/JS use the built file’s modification time as the `ver` query string. Each `npm run build` updates cache; no manual version bump.

## Dominant Color Images

Dominant color is calculated on upload (Imagick or GD), stored in post meta `_dominant_color`, and used as a background placeholder until the image loads. File: `inc/performance/dominant-color-images.php`. Supports standard image types and core image/cover blocks.

## Image Prioritizer

Adds `fetchpriority="high"` to above-the-fold images (featured + up to a few from content) on singular pages; removes `loading="lazy"` for those. File: `inc/performance/image-prioritizer.php`. Complements LCP optimization.

## WebP Uploads

Generates WebP for JPEG/PNG uploads (all sizes). Imagick or GD. Helpers: `\WebDevStudios\wdsbt\get_webp_url()`, `get_webp_file()`, `browser_supports_webp()`. Regenerate via Tools > WDSBT Settings, WP-CLI `wp webp regenerate`, or `regenerate_webp_for_attachment()`. File: `inc/performance/webp-uploads.php`.
