# WDS BT

## Version: 1.4.0

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png)](https://webdevstudios.com/contact/)

### What's New in 1.4.0

- **Block Showcase**: Powerful development tool (admin-only) to discover, preview, and inspect all registered blocks with their attributes in an organized, interactive format.
- **Template-specific style loading**: Template and CPT CSS are built separately and enqueued only when the current request matches (404, search, archive, page templates, custom post types). Styles in `build/css/templates/` are discovered automatically—no PHP config needed. Reduces unused CSS for better Lighthouse scores.
- **Automatic cache versioning**: All theme-built CSS and JS use file modification time as the version query string so caches update after each build without manual version bumps.
- **Lighthouse script**: Run `npm run lighthouse` to audit Performance, Accessibility, Best Practices, and SEO for mobile and desktop; scores printed in the terminal only. URL via prompt or CLI argument.
- **Dominant Color Images**: Automatic calculation and storage of dominant colors for uploaded images, used as placeholders while images load.
- **Image Prioritizer**: Automatically prioritizes above-the-fold images with fetchpriority="high" for improved page load performance.
- **WebP Uploads**: Automatically generates WebP versions of uploaded JPEG and PNG images for better compression and faster loading.
- **ESLint 9 Migration**: Upgraded to ESLint 9 with flat config format for modern JavaScript linting.

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Code Quality](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml)
[![Security](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml)

<details>
 <summary><b>Table of Contents</b></summary>

- [Overview](#overview)
- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Documentation](#documentation)
- [Development](#development)
- [Font Management](#font-management)
- [Version Management](#version-management)
- [Creating Blocks](#creating-blocks)
- [Block Showcase](#block-showcase)
- [Performance & Images](#performance--images)
- [Customizations](#customizations)
- [Linting & Lefthook](#linting--lefthook)
- [Dynamic Block Pattern Categories](#dynamic-block-pattern-categories)
- [Accessibility & Quality](#accessibility--quality)
- [Contributing and Support](#contributing-and-support)
- [Acknowledgements](#acknowledgements)

</details>

***

## Overview

WDS BT is a foundational WordPress block theme designed for maximum flexibility and customization. It integrates seamlessly with the native WordPress block editor, providing an intuitive and adaptable user experience. WDS BT is specifically developed as a foundational rather than parent theme, giving developers a clean and versatile base for advanced customizations.

| Feature                                          | Description                                                                                         |
|--------------------------------------------------|-----------------------------------------------------------------------------------------------------|
| Native Block Support                             | Built for native WordPress blocks and site editor integration.                                      |
| Responsive Design                                | Ensures optimal display and functionality across devices.                                           |
| Foundation Theme                                 | Flexible base theme optimized for extensive customization.                                          |
| Automated Code Quality                           | Modern linting configurations with PHP 8.3 compatibility, ESLint 9 flat config, WordPress coding standards, and automated quality checks. |
| Cross-Platform PHP Support                       | Automatic PHP binary detection and extension handling for Mac, Linux, and CI/CD environments.       |
| Third-party Block Style Overrides                | Conditionally enqueue and override third-party block styles for efficient asset delivery.           |
| Accessibility Compliance                         | Built-in WCAG 2.2 compliance with automated Pa11y checks.                                           |
| Enhanced Webpack Configuration                   | Refined Webpack setup for improved dependency resolution and optimized asset management.            |
| Block Creation Script Enhancements               | Options for static, dynamic, or interactive blocks; automatically includes `view.js` for rendering. |
| Block Showcase                                   | Development tool to discover, preview, and inspect all registered blocks with their attributes.  |
| Dominant Color Images                            | Automatic dominant color calculation for images, used as background placeholders during image loading. |
| Image Prioritizer                                | Automatically prioritizes above-the-fold images with fetchpriority="high" for faster page loads. |
| WebP Uploads                                     | Automatically generates WebP versions of uploaded images for better compression and performance. |
| Template-specific style loading                  | Template and CPT styles are loaded only when needed (404, search, archive, page templates, custom post types); improves Lighthouse by reducing unused CSS. |
| Automatic cache versioning                       | Built CSS/JS use file modification time as the version query string so caches update automatically after each build. |
| Lighthouse                                       | Run Performance, Accessibility, Best Practices, and SEO audits (mobile + desktop) from the terminal; scores only, no report files. |
| Lefthook Integration                             | Required for pre-commit hooks and automated code quality checks.                                           |

## Requirements

- WordPress 6.4+ (tested upto 6.9.1)
- PHP 8.2+ (fully tested with PHP 8.4)
- [NPM](https://npmjs.com) (v11+)
- [Node](https://nodejs.org) (v25+)
- [Composer 2+](https://getcomposer.org/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

## Getting Started

1. Clone this repository to your WordPress theme directory (`wp-content/themes/`).
2. Activate WDS BT from your WordPress admin panel under Appearance > Themes.
3. Run `npm run setup` to install dependencies and perform an initial build.

## Documentation

Full documentation is in the **[docs/](docs/README.md)** folder. Use it for setup details, NPM scripts, fonts, version management, linting and Lefthook, performance and images, Block Showcase, customizations, and accessibility/quality.

| Doc | Description |
|-----|-------------|
| [Development](docs/development.md) | Theme structure, setup, NPM scripts |
| [Font Management](docs/fonts.md) | Font organization, tools, workflow |
| [Version Management](docs/version-management.md) | Version bump and release workflows |
| [Linting & Lefthook](docs/linting-and-hooks.md) | PHP/JS/CSS linting, strict mode, fix-on-commit, no-verify policy |
| [Performance & Images](docs/performance.md) | Asset loading, dominant color, prioritizer, WebP |
| [Block Showcase](docs/block-showcase.md) | Admin block discovery and preview tool |
| [Customizations](docs/customizations.md) | Block styles, variations, mixins |
| [Accessibility & Quality](docs/accessibility-and-quality.md) | A11y, Lighthouse, security, cross-platform |

## Development

Theme structure, setup steps, and a full NPM scripts reference are in **[docs/development.md](docs/development.md)**. Quick start: `cd` to the theme directory, then `npm run setup`.

## Font Management

Fonts are organized by purpose (`headline/`, `body/`, `mono/` under `assets/fonts/`). Run `npm run fonts` to process them and update `theme.json` and preload links. Full workflow and tools: **[docs/fonts.md](docs/fonts.md)**.

## Version Management

Set `VERSION=1.4.0` in `.env` (or use the env var), then run `npm run version-update` to sync version in `style.css`, `package.json`, `composer.json`, and README. Release workflows and troubleshooting: **[docs/version-management.md](docs/version-management.md)**.

## Creating Blocks

Run `npm run create-block` from the theme root and follow the prompts; the block is scaffolded under `assets/blocks/`. Then run `npm run build` to compile assets. See [Development](docs/development.md) for the full script list.

## Block Showcase

Admin-only tool to list and preview all registered blocks with their attributes. Create a page and assign the **Block Showcase** template. Full steps and options: **[docs/block-showcase.md](docs/block-showcase.md)**.

## Performance & Images

Template and CPT styles load only when relevant; block styles are enqueued per block or when used. Built assets use modification-time cache versioning. Dominant color placeholders, image prioritizer, and WebP generation are built in. Details: **[docs/performance.md](docs/performance.md)**.

## Customizations

Register block styles in `inc/hooks/register-block-styles.php`. Override core blocks with SCSS in `assets/scss/blocks/core/`, third-party in `assets/scss/blocks/third-party/`. Add variations in `assets/js/block-variations/` and unregister blocks in `assets/js/editor.js`. Responsive and mobile-only mixins are in `assets/scss/abstracts/`. Full guide: **[docs/customizations.md](docs/customizations.md)**.

## Linting & Lefthook

PHP (PHPCS), JavaScript (ESLint), and CSS/SCSS (Stylelint) use the latest WordPress standards; warnings are treated as failures. On commit, Lefthook runs auto-fix, re-stages changed files, then runs strict lint. Do not use `--no-verify`; CI runs the same checks. Full config and policy: **[docs/linting-and-hooks.md](docs/linting-and-hooks.md)**.

## Dynamic Block Pattern Categories

Block pattern categories are registered from subfolders of `patterns/`. Add a folder (e.g. `patterns/cards/`), put pattern PHP files in it, and set each file’s `Categories` header to the folder name (e.g. `Categories: cards`). No extra code is required.

## Accessibility & Quality

Run `npm run a11y` for Pa11y-based accessibility checks and `npm run lighthouse` for performance and SEO scores. CI runs security and code-quality checks. Cross-platform notes (rimraf, PHP detection) are in **[docs/accessibility-and-quality.md](docs/accessibility-and-quality.md)**.

## Contributing and Support

Contributions and [issues](https://github.com/WebDevStudios/wds-bt/issues) are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md) before opening a pull request.

WDS BT is free software under the GPL v2 or later. See [LICENSE.md](LICENSE.md).

***

## Acknowledgements

The WDS-BT theme was initially inspired by the [Powder](https://github.com/bgardner/powder) theme. We thank the creators of Powder for their work.

***

[🔝 Back to Top](#wds-bt)
