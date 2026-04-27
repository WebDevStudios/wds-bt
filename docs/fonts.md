# Font Management

Fonts are organized by purpose under `assets/fonts/`: `headline/`, `body/`, `mono/`. **Production and dev builds** (`npm run build` / `npm run start`) copy those files into `build/fonts/` via webpack, so PHP does not need to run on every compile. The `npm run fonts` tool is **optional** for day-to-day builds; use it when you add, remove, or rename font files and want to regenerate `inc/setup/font-preload.php` and refresh `theme.json` from disk (see tools). Runtime font registration also considers `assets/fonts/` and `build/fonts/` via `inc/setup/dynamic-theme-json.php`. Use in SCSS as `var(--wp--preset--font-family--headline)` etc.

## Commands

- **`npm run build` / `npm run start`** — Bundles the theme and copies `assets/fonts/**/*` into `build/fonts/` (same layout as source).
- **`npm run fonts`** — PHP: scan `assets/fonts/`, copy to `build/fonts/`, regenerate preload PHP, update `theme.json`. Run when the font set changes and you want generated artifacts refreshed.
- **`npm run fonts:detect`** — List detected fonts (paths, family, weight, style).
- **`npm run fonts:generate`** — Regenerate `theme.json` font families from scanned font files (`php tools/generate-theme-json.php`).

## Workflow

1. Place font files in the correct folder (`headline/`, `body/`, `mono/`).
2. Run `npm run build` (or `npm run start`); confirm `build/fonts/` contains the expected files.
3. When you change which fonts exist or their filenames, run **`npm run fonts`** (or at least `npm run fonts:generate` if you only need `theme.json`), then commit updated `theme.json` if applicable. Check `inc/setup/font-preload.php` after `npm run fonts` (that file is gitignored; generate it locally or in CI if you rely on preloads).

## File Naming

Family, weight, and style are inferred from folder and filename (e.g. weights 100–900, styles normal/italic/oblique). See `inc/setup/dynamic-theme-json.php` and tools for patterns.

## Debug (WP_DEBUG)

When `WP_DEBUG` is true, the WDSBT Settings page can show font detection debug: counts, families, variants. If fonts don’t appear, confirm folder names and supported extensions (e.g. `.woff2`, `.woff`), and run `npm run fonts:detect`.
