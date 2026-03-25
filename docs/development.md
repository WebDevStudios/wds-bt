# Development

## Theme Structure

```
└── wds-bt
    └── assets (fonts, images, js, scss)
    └── inc (block-template, functions, hooks, setup)
    └── parts (comments, footer, header, post-meta)
    └── patterns (cards, content, footers, headers, posts)
    └── styles (e.g. dark.json)
    └── templates (404, archive, index, page-*, search, single)
    └── Root config (composer, package, webpack, phpcs, eslint, stylelint, lefthook, etc.)
```

See the [main README](../README.md) Table of Contents for the full tree, or inspect the repo.

## Setup

1. Clone the theme to `wp-content/themes/your-theme`.
2. From the theme directory, run:

```bash
npm run setup
```

This removes `node_modules`, `vendor`, `build`, `package-lock.json`, and `composer.lock`, then installs dependencies, runs an initial build, and runs `fonts:generate` to refresh `theme.json` font families (that step is not part of `npm run build` alone).

**Note:** Composer 2 and NPM 11+ are required. Use the theme’s [.devcontainer](../.devcontainer) for a theme-only workflow (PHP 8.2, Node 24; no local install)—works for anyone. Or install PHP 8.2+ and Node 24+ natively.

## NPM Scripts

| Command | Description |
|--------|--------------|
| `npm run a11y` | Run accessibility tests (Pa11y-CI). |
| `npm run build` | Build theme assets. |
| `npm run create-block` | Scaffold a new block. |
| `npm run format` | Format JS, SCSS, and PHP. |
| `npm run format:css` | Format SCSS. |
| `npm run format:js` | Format JavaScript. |
| `npm run format:php` | Format PHP. |
| `npm run fonts` | Process fonts and update theme.json. |
| `npm run fonts:detect` | Detect and list fonts. |
| `npm run fonts:generate` | Generate theme.json with detected fonts. |
| `npm run lighthouse` | Run Lighthouse (mobile + desktop); scores in terminal. |
| `npm run lint` | Run all linting. |
| `npm run lint:css` | Lint CSS/SCSS. |
| `npm run lint:js` | Lint JavaScript. |
| `npm run lint:php` | Lint PHP. |
| `npm run packages-update` | Update dependencies in package.json. |
| `npm run setup` | Reset, install, and build. |
| `npm run start` | Start the development server. |
| `npm run version-update` | Update theme version from .env. |
