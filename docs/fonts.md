# Font Management

Fonts are organized by purpose under `assets/fonts/`: `headline/`, `body/`, `mono/`. The processor copies them to `build/fonts/`, updates `inc/setup/font-preload.php`, and writes font families into `theme.json` with slugs `headline`, `body`, `mono`. Use in SCSS as `var(--wp--preset--font-family--headline)` etc.

## Commands

- **`npm run fonts`** — Scan `assets/fonts/`, copy to `build/fonts/`, generate preload links, update theme.json.
- **`npm run fonts:detect`** — List detected fonts (paths, family, weight, style).
- **`npm run fonts:generate`** — Advanced: subsetting, multiple formats, CSS/preload generation (see tools).

## Workflow

1. Place font files in the correct folder (`headline/`, `body/`, `mono/`).
2. Run `npm run fonts`.
3. Check `build/fonts/`, `inc/setup/font-preload.php`, and `theme.json`.

## File Naming

Family, weight, and style are inferred from folder and filename (e.g. weights 100–900, styles normal/italic/oblique). See `inc/setup/dynamic-theme-json.php` and tools for patterns.

## Debug (WP_DEBUG)

When `WP_DEBUG` is true, the WDSBT Settings page can show font detection debug: counts, families, variants. If fonts don’t appear, confirm folder names and supported extensions (e.g. `.woff2`, `.woff`), and run `npm run fonts:detect`.
