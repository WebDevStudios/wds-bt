# WDS BT

## Version: 1.3.5

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png)](https://webdevstudios.com/contact/)

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Code Quality](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml)
[![Security](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml)

<details>
 <summary><b>Table of Contents</b></summary>
 <a name="back-to-top"></a>

- [WDS BT](#wds-bt)
  - [Overview](#overview)
  - [Requirements](#requirements)
  - [Getting Started](#getting-started)
  - [Development](#development)
    - [Theme Structure](#theme-structure)
    - [Setup](#setup)
    - [NPM Scripts](#npm-scripts)
  - [Font Management](#font-management)
    - [Font Organization](#font-organization)
    - [Font Tools](#font-tools)
    - [Font Workflow](#font-workflow)
  - [Creating Blocks](#creating-blocks)
  - [Customizations](#customizations)
    - [Registering Block Styles](#registering-block-styles)
    - [Overriding/Customizing Core Block Styles](#overridingcustomizing-core-block-styles)
    - [Overriding/Customizing Third Party Block Styles](#overridingcustomizing-third-party-block-styles)
    - [Creating Block Variations](#creating-block-variations)
    - [Unregister Blocks and Variations](#unregister-blocks-and-variations)
  - [Stylelint Configuration](#stylelint-configuration)
    - [Extending WordPress Stylelint Rules](#extending-wordpress-stylelint-rules)
    - [Running Stylelint](#running-stylelint)
  - [PHP Linting Configuration](#php-linting-configuration)
    - [PHP Compatibility](#php-compatibility)
    - [Running PHP Linting](#running-php-linting)
  - [JavaScript Linting Configuration](#javascript-linting-configuration)
    - [ESLint Setup](#eslint-setup)
    - [Running JavaScript Linting](#running-javascript-linting)
  - [Dynamic Block Pattern Categories](#dynamic-block-pattern-categories)
  - [Accessibility, Code Quality, and Security Checks](#accessibility-code-quality-and-security-checks)
  - [Strict Lefthook Integration](#strict-lefthook-integration)
  - [Cross-Platform Compatibility](#cross-platform-compatibility)
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
| Automated Code Quality                           | Modern linting configurations with PHP 8.3 compatibility, WordPress coding standards, and automated quality checks. |
| Third-party Block Style Overrides                | Conditionally enqueue and override third-party block styles for efficient asset delivery.           |
| Accessibility Compliance                         | Built-in WCAG 2.2 compliance with automated Pa11y checks.                                           |
| Enhanced Webpack Configuration                   | Refined Webpack setup for improved dependency resolution and optimized asset management.            |
| Block Creation Script Enhancements               | Options for static, dynamic, or interactive blocks; automatically includes `view.js` for rendering. |
| LeftHook Integration                             | Required for pre-commit hooks and automated code quality checks.                                           |

## Requirements

- WordPress 6.4+
- PHP 8.2+ (fully tested with PHP 8.3)
- [NPM](https://npmjs.com) (v10+)
- [Node](https://nodejs.org) (v20+)
- [Composer 2+](https://getcomposer.org/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

## Getting Started

1. Clone this repository to your WordPress theme directory (`wp-content/themes/`).
2. Activate WDS BT from your WordPress admin panel under Appearance > Themes.
3. Run `npm run setup` to install dependencies and perform an initial build.

## Development

[🔝 Back to Top](#wds-bt)

<details closed>
  <summary><b>Theme Structure</b></summary>
 <pre>
  <code>
└── 📁wds-bt
 └── 📁assets
  └── 📁fonts
  └── 📁images
   └── 📁icons
  └── index.js
  └── 📁js
   └── 📁block-filters
    └── buttons.js
    └── index.js
   └── 📁block-variations
    └── index.js
   └── editor.js
   └── 📁global
    └── header.js
    └── index.js
    └── table.js
   └── index.js
   └── 📁templates
    └── index.js
  └── 📁scss
   └── _index.scss
   └── 📁abstracts
    └── _index.scss
    └── mobile-only-mixins.scss
    └── responsive-mixins.scss
    └── utility.scss
   └── 📁base
    └── _index.scss
    └── forms.scss
    └── global.scss
    └── pagination.scss
   └── 📁blocks
    └── 📁core
    └── 📁third-party
   └── editor.scss
   └── 📁patterns
    └── _index.scss
   └── 📁template-parts
    └── _index.scss
    └── footer.scss
    └── header.scss
   └── 📁templates
    └── _index.scss
    └── 404.scss
    └── archive.scss
    └── search.scss
 └── 📁inc
  └── 📁block-template
   └── 📁block
    └── edit.js.mustache
    └── editor.scss.mustache
    └── index.js.mustache
    └── render.php.mustache
    └── style.scss.mustache
    └── view.js.mustache
   └── index.js
   └── 📁plugin
    └── .editorconfig.mustache
    └── .eslintrc.mustache
    └── .gitignore.mustache
    └── $slug.php.mustache
    └── readme.txt.mustache
   └── README.md
  └── 📁functions
   └── back-to-top.php
   └── custom-logo-svg.php
   └── security.php
  └── 📁hooks
   └── enqueue-block-stylesheet.php
   └── enqueue-third-party-block-stylesheet.php
   └── register-block-categories.php
   └── register-block-filters.php
   └── register-block-pattern-categories.php
   └── register-block-patterns.php
   └── register-block-styles.php
   └── register-block-variations.php
   └── restrict-block-patterns.php
  └── 📁setup
   └── scripts.php
   └── setup.php
   └── style-script-version.php
 └── 📁pa11y-ci-report
 └── 📁parts
  └── comments.html
  └── footer.html
  └── header.html
  └── post-meta.html
 └── 📁patterns
  └── comments.php
  └── footer-default.php
  └── header-default.php
  └── post-hero.php
  └── primary-category.php
 └── 📁styles
  └── dark.json
 └── 📁templates
  └── 404.html
  └── archive.html
  └── index.html
  └── page-blank.html
  └── page-no-title.html
  └── page.html
  └── search.html
  └── single.html
 └── .editorconfig
 └── .env
 └── .eslintignore
 └── .eslintrc.js
 └── .gitignore
 └── .markdownlintignore
 └── .nvmrc
 └── .prettierignore
 └── .prettierrc.js
 └── .stylelintignore
 └── .stylelintrc.json
 └── a11y.cjs
 └── babel.config.json
 └── composer.json
 └── composer.lock
 └── CONTRIBUTING.md
 └── functions.php
 └── lefthook.yml
 └── LICENSE.md
 └── package-lock.json
 └── package.json
 └── phpcs.xml.dist
 └── postcss.config.js
 └── README.md
 └── readme.txt
 └── screenshot.png
 └── style.css
 └── theme.json
 └── updateVersion.js
 └── webpack.config.js
  </code>
 </pre>
</details>

<details closed>
<summary><b>Setup</b></summary>

From the command line, change directories to your new theme directory:

```bash
cd /wp-content/themes/your-theme
```

The command below will remove `node_modules`, `vendor`, `build`, `package-lock.json`, and `composer.lock` files. Install theme dependencies and trigger an initial build.

>Note: You will need to have Composer 2 and NPM 10 installed first.

```bash
npm run setup
```

</details>

## NPM Scripts

[🔝 Back to Top](#wds-bt)

*From the command line, type any of the following to perform an action:*

|     | Command                     | Description                                             |
|-----|-----------------------------|-------------------------------------------------------- |
| 🌐   | `npm run a11y`              | Run accessibility tests (Pa11y-CI).                     |
| 🛠️  | `npm run build`             | Build the theme assets.                                 |
| 🔨  | `npm run create-block`      | Scaffold a new block with various configurations.       |
| 📝  | `npm run format`            | Format all code files (JS, SCSS, PHP).                  |
| 🎨  | `npm run format:css`        | Format SCSS files.                                      |
| 🐘  | `npm run format:php`        | Format PHP files.                                       |
| 🔤  | `npm run fonts`             | Process fonts and update theme.json.                    |
| 🔍  | `npm run fonts:detect`      | Detect and list all available fonts.                    |
| 🔧  | `npm run fonts:generate`    | Generate theme.json with detected fonts.                |
| 🔍  | `npm run lint`              | Run all linting scripts.                                |
| 🎨  | `npm run lint:css`          | Lint CSS files.                                         |
| 🚀  | `npm run lint:js`           | Lint JavaScript files.                                  |
| 🐘  | `npm run lint:php`          | Lint PHP files.                                         |
| 🔄  | `npm run packages-update`   | Update dependencies defined in `package.json`.          |
| 🛠️  | `npm run setup`             | Reset, install dependencies, and build the theme.       |
| ▶️  | `npm run start`             | Start the development server.                           |
| 🔖  | `npm run version-update`    | Update the theme version based on environment variable. |

## Font Management

[🔝 Back to Top](#wds-bt)

WDS BT includes an automated font management system that organizes fonts by purpose and automatically generates the necessary files for optimal font loading and WordPress integration.

<details closed>
<summary><b>Font Organization</b></summary>

Fonts are organized by their purpose/role for easy swapping and management:

```
assets/fonts/
├── headline/
│   ├── Inter.woff2
│   └── Poppins-Bold.woff2
├── body/
│   ├── Oxygen-Regular.woff2
│   └── OpenSans-Regular.woff2
└── mono/
    └── RobotoMono-Regular.woff2
```

**Benefits:**

- **Easy font swapping**: Just replace files in each folder
- **Clear purpose**: Each folder has a specific role
- **Standardized slugs**: Automatically generates `headline`, `body`, `mono` slugs in theme.json
- **Consistent paths**: CSS custom properties always use the same slugs

**How it works:**

1. Place fonts in the appropriate purpose folder (`headline/`, `body/`, `mono/`)
2. The processor detects the purpose from the folder name
3. Maps to standardized slugs in theme.json
4. Generates correct CSS custom properties: `var(--wp--preset--font-family--headline)`, `var(--wp--preset--font-family--body)`, `var(--wp--preset--font-family--mono)`

**To change fonts:**

- **Headlines**: Replace files in `headline/` folder
- **Body text**: Replace files in `body/` folder
- **Code/mono**: Replace files in `mono/` folder

</details>

<details closed>
<summary><b>Font Tools</b></summary>

### Font Processor (`npm run fonts`)

The main font processing tool that:

- Scans `assets/fonts/` for font files
- Copies fonts to `build/fonts/` maintaining folder structure
- Generates preload links in `inc/setup/font-preload.php`
- Updates `theme.json` with detected font families

**Usage:**

```bash
npm run fonts
# or
php tools/font-processor.php
```

### Font Detection (`npm run fonts:detect`)

Lists all available fonts in your theme for debugging and inspection.

**Usage:**

```bash
npm run fonts:detect
# or
php tools/font-detection.php
```

**Output:**

- Lists all fonts found in `assets/fonts/` and `build/fonts/`
- Shows font family, weight, and style information
- Helps identify what fonts are available

### Font Generator (`npm run fonts:generate`)

Advanced font processing with optimization and subsetting capabilities.

**Usage:**

```bash
npm run fonts:generate
# or
php tools/font-generator.php
```

**Features:**

- Font subsetting for smaller file sizes
- Multiple format generation (WOFF2, WOFF)
- CSS generation with @font-face declarations
- Preload link generation

</details>

<details closed>
<summary><b>Font Workflow</b></summary>

### Adding New Fonts

1. **Organize by purpose**: Place font files in the appropriate folder:
   - `assets/fonts/headline/` for heading fonts
   - `assets/fonts/body/` for body text fonts
   - `assets/fonts/mono/` for monospace/code fonts

2. **Run the font processor**:

   ```bash
   npm run fonts
   ```

3. **Verify the results**:
   - Check `build/fonts/` for copied fonts
   - Check `inc/setup/font-preload.php` for preload links
   - Check `theme.json` for font family definitions

### Using Fonts in Your Theme

Fonts are automatically available as CSS custom properties:

```scss
.heading {
  font-family: var(--wp--preset--font-family--headline);
}

.body-text {
  font-family: var(--wp--preset--font-family--body);
}

.code {
  font-family: var(--wp--preset--font-family--mono);
}
```

### Font File Naming

The system automatically detects font properties from filenames:

- **Family detection**: From folder name or filename patterns
- **Weight detection**: From filename patterns (e.g., `-300`, `-bold`, `-700`)
- **Style detection**: From filename patterns (e.g., `-italic`, `-oblique`)

**Supported patterns:**

- Weights: `100`, `200`, `300`, `400`, `500`, `600`, `700`, `800`, `900`
- Styles: `normal`, `italic`, `oblique`
- Families: `Inter`, `Oxygen`, `Roboto Mono`, `Open Sans`, `Lato`, `Poppins`, etc.

</details>

<details closed>
<summary><b>Font Debugging</b></summary>

### Debug Information

When `WP_DEBUG` is enabled, font detection debug information is displayed in the WordPress admin on the WDSBT Settings page.

**To enable debug:**

1. Set `WP_DEBUG = true` in your `wp-config.php`
2. Visit the WDSBT Settings page in WordPress admin
3. Look for the "Font Detection Debug" notice

**Debug information includes:**

- Number of fonts found in build and assets directories
- List of detected font families with variant counts
- Individual font variant details (weight, style, file path)

### Troubleshooting

**Fonts not appearing in theme.json:**

1. Check that fonts are in the correct folders (`headline/`, `body/`, `mono/`)
2. Verify font file extensions are supported (`.woff2`, `.woff`, `.ttf`, `.otf`)
3. Run `npm run fonts:detect` to see what fonts are detected
4. Check for any error messages in the font processor output

**Preload links not working:**

1. Ensure `inc/setup/font-preload.php` exists and is properly generated
2. Check that the file is being included in your theme
3. Verify the font paths in the preload links are correct

**CSS custom properties not available:**

1. Check that `theme.json` contains the font family definitions
2. Verify the font slugs are correct (`headline`, `body`, `mono`)
3. Ensure WordPress is generating the CSS custom properties

</details>

## Version Management

[🔝 Back to Top](#wds-bt)

The theme includes an automated version update system that ensures consistency across all files that reference the theme version.

<details closed>
<summary><b>How to Update the Theme Version</b></summary>

**Method 1**: Using the .env file (Recommended)

1. **Update the `.env` file** with the new version:

   ```bash
   echo "VERSION=1.4.0" > .env
   ```

2. **Run the version update script**:

   ```bash
   npm run version-update
   ```

**Method 2**: Using environment variable directly

```bash
VERSION=1.4.0 npm run version-update
```

**Method 3**: Using dotenv-cli (if installed globally)

```bash
npx dotenv -e .env -- npm run version-update
```

</details>

<details closed>
<summary><b>What Gets Updated</b></summary>

The version update script automatically updates the version in:

- `style.css` (theme header)
- `package.json` (NPM package version)
- `composer.json` (Composer package version)
- `README.md` (documentation version)

#### Complete Workflow Example

```bash
# 1. Set the new version in .env file
echo "VERSION=1.4.0" > .env

# 2. Run the version update script
npm run version-update

# 3. Verify the changes
git diff

# 4. Commit the version bump
git add .
git commit -m "WDSBT-XXX - bump version to 1.4.0"

# 5. Tag the release (optional)
git tag -a v1.4.0 -m "Release version 1.4.0"
git push origin v1.4.0
```

</details>

<details closed>
<summary><b>Release Types and Workflows</b></summary>

**Patch Release (Bug Fixes)**
For small bug fixes and minor updates (e.g., 1.4.0 → 1.4.1):

```bash
# 1. Create a patch branch
git checkout -b patch/1.4.1

# 2. Make your bug fixes
# ... make changes ...

# 3. Update version to patch
echo "VERSION=1.4.1" > .env
npm run version-update

# 4. Commit changes
git add .
git commit -m "WDSBT-XXX - fix [specific issue]"

# 5. Commit version bump
git add .
git commit -m "WDSBT-XXX - bump version to 1.4.1"

# 6. Create pull request
git push origin patch/1.4.1
# ... create PR and merge ...

# 7. Tag the release
git checkout main
git pull origin main
git tag -a v1.4.1 -m "Patch release 1.4.1 - [brief description]"
git push origin v1.4.1
```

**Minor Release (New Features)**
For new features and enhancements (e.g., 1.4.0 → 1.5.0):

```bash
# 1. Create a feature branch
git checkout -b feature/1.5.0

# 2. Add new features
# ... implement features ...

# 3. Update version to minor
echo "VERSION=1.5.0" > .env
npm run version-update

# 4. Update CHANGELOG.md (if maintained)
# ... document new features ...

# 5. Commit changes
git add .
git commit -m "WDSBT-XXX - add [new feature]"

# 6. Commit version bump
git add .
git commit -m "WDSBT-XXX - bump version to 1.5.0"

# 7. Create pull request and tag release
# ... same as patch workflow ...
```

**Major Release (Breaking Changes)**
For major updates with breaking changes (e.g., 1.4.0 → 2.0.0):

```bash
# 1. Create a major release branch
git checkout -b release/2.0.0

# 2. Implement breaking changes
# ... make breaking changes ...

# 3. Update version to major
echo "VERSION=2.0.0" > .env
npm run version-update

# 4. Update documentation for breaking changes
# ... update README, CHANGELOG, etc. ...

# 5. Test thoroughly
npm run build
npm run lint
npm run a11y

# 6. Commit changes
git add .
git commit -m "WDSBT-XXX - breaking: [description of breaking changes]"

# 7. Commit version bump
git add .
git commit -m "WDSBT-XXX - bump version to 2.0.0"

# 8. Create pull request and tag release
# ... same as patch workflow ...
```

</details>

<details closed>
<summary><b>Automated Patch Workflow</b></summary>

For quick patches, you can use a streamlined workflow:

```bash
# Quick patch workflow
git checkout -b hotfix/1.4.1
# ... make quick fix ...
echo "VERSION=1.4.1" > .env && npm run version-update
git add . && git commit -m "WDSBT-XXX - hotfix: [issue description]"
git push origin hotfix/1.4.1
# ... create PR, merge, tag ...
```

</details>

<details closed>
<summary><b>Pre-release Versions</b></summary>

For beta, alpha, or release candidate versions:

```bash
# Beta release
echo "VERSION=1.4.0-beta.1" > .env
npm run version-update

# Alpha release
echo "VERSION=1.4.0-alpha.1" > .env
npm run version-update

# Release candidate
echo "VERSION=1.4.0-rc.1" > .env
npm run version-update
```

</details>

<details closed>
<summary><b>Troubleshooting</b></summary>

**Version not updating in all files:**

1. Check that the `.env` file exists and contains the `VERSION` variable
2. Ensure the version format is correct (e.g., `1.4.0`, not `v1.4.0`)
3. Run `npm run version-update` with verbose output to see any errors

**Permission errors:**

1. Ensure you have write permissions to all theme files
2. Check that no files are locked by other processes

**Script not found:**

1. Verify that `updateVersion.js` exists in the theme root
2. Ensure Node.js is installed and accessible
3. Run `npm install` to ensure all dependencies are installed

</details>

<details closed>
<summary><b>Version Update Process Details</b></summary>

The version update script (`updateVersion.js`) reads the `VERSION` environment variable from the `.env` file and updates all version references across the project. This ensures consistency across all files that reference the theme version.

**How it works:**

1. Reads the `VERSION` environment variable
2. Scans specific files for version patterns
3. Updates version references while preserving formatting
4. Provides feedback on what was updated

**Supported version formats:**

- Semantic versioning: `1.4.0`, `2.0.0`, `1.4.0-beta.1`
- WordPress version format: `1.4.0`

**Files processed:**

- `style.css` - WordPress theme header
- `package.json` - NPM package metadata
- `composer.json` - Composer package metadata
- `README.md` - Documentation version references

</details>

## Creating Blocks

[🔝 Back to Top](#wds-bt)

1. Run the Block Creation Script
   Navigate to your project root in the terminal and run the following command to create a new block:

```bash
npm run create-block
```

Follow the prompts to configure your new block. The script will scaffold a new block structure inside assets/blocks/.

2. Build your block
   After editing and configuring your block, build your project to compile assets using webpack:

```bash
npm run build
```

This will process JavaScript, SCSS, optimize images, and generate necessary files for each custom block in the `./blocks` directory.

## Customizations

[🔝 Back to Top](#wds-bt)

<details closed>
<summary><b>Registering Block Styles</b></summary>

1. Open the `inc/hooks/register-block-styles.php` file in your theme directory.

2. Add a new block style entry with the following mandatory properties:

- **Name:** The identifier used to compute a CSS class for the style.

- **Label:** A human-readable label for the style.

 Example:

 ```php
 'block_name' => array(
  'name' => __( 'label', 'wdsbt' ),
 ),
 ```

</details>

<details closed>
<summary><b>Overriding/Customizing Core Block Styles</b></summary>

1. Navigate to the `assets/scss/blocks/core` directory within your theme.

2. Create an SCSS file with the exact filename as the block name you want to customize. This file will house your custom styles for that specific block.

3. Files within the `assets/scss/blocks/core/` directory are automatically enqueued, simplifying the integration of your custom styles into the WordPress block editor. Do not import these files into the main `index.scss`

4. After adding your custom SCSS file, run the following command to compile and apply your changes:

 ```bash
 npm run build
 ```

</details>

<details closed>
<summary><b>Overriding/Customizing Third Party Block Styles</b></summary>

1. **Place Your Override SCSS Files**: Add your third‑party override SCSS files in `assets/scss/blocks/third-party/`.
   - **Naming Convention:** Name each file using the block's slug (the part after the namespace). For example, to override the `wdsbt/test` block, name the file `test.scss`.

2. **Third-Party Block Styles**: Files within the `assets/scss/blocks/third-party/` directory are automatically enqueued. Do not import these files into your main `index.scss`.

3. **Compile Your Changes**: After adding or updating your custom SCSS file, run the following command to compile and apply your changes:

 ```bash
 npm run build
 ```

</details>

<details closed>
<summary><b>Creating Block Variations</b></summary>

1. In the `assets/js/block-variations` directory within your theme, create a new JavaScript file. This file will contain the definition of your block variation.

2. Import the newly created file into the `assets/js/block-variations/index.js` file. This step ensures that your variation is included in the build process.

3. Use the `wp.blocks.registerBlockVariation()` function to officially register your block variation. This function takes the name of the original block and an object defining the variation.

 Example:

 ```javascript
 // In your variations JavaScript file
 wp.blocks.registerBlockVariation('core/paragraph', {
  name: 'custom-variation',
  title: __('Custom Variation', 'wdsbt'),
  attributes: { /* Define your custom attributes here */ },
  // Add more variation settings as needed
 });
 ```

 **Original Block Name**: Provide the name of the original block for which you are creating the variation.
 **Variation Object**: Define the properties of your block variation, including the name, title, attributes, and any additional settings.

> ***NOTE:*** To find the correct block name, open the block editor, launch the browser console and type `wp.blocks.getBlockTypes()`. You will see the complete list of block names (from core or third-party).

</details>

<details closed>
<summary><b>Unregister Blocks and Variations</b></summary>

This functionality allows you to unregister and disable specific core Gutenberg blocks, styles, and variations that are not needed on your WordPress website. By removing these unused blocks and variations, you can streamline the Gutenberg editor and improve the overall performance of your site.

#### Implementation

The script in `assets/js/editor.js` loops through a list of unused blocks and variations, unregistering them from the Gutenberg editor. Additionally, it keeps only the specified embed variations for the core/embed block.

<b>Example</b>

```javascript
// List of Gutenberg blocks to unregister
const unusedBlocks = [
 'core/file',
 'core/latest-comments',
 'core/rss',
 // Add more unused blocks as needed
];

// List of Gutenberg block variations to unregister
const unregisterBlockVariations = [
 // Example:
 // {
 //     blockName: 'core/group',
 //     blockVariationName: 'group-stack',
 // },
];

// Keep only the necessary embed variations
const keepEmbeds = [
 'twitter',
 'wordpress',
 'spotify',
 // Add more necessary embed variations as needed.
];
```

</details>

## Mixins

[🔝 Back to Top](#wds-bt)

<details closed>
<summary><b>Responsive Mixins</b></summary>

This SCSS file `assets/scss/abstracts/responsive-mixins.scss` provides mixins for creating responsive media queries with both minimum and maximum width conditions. The file promotes modular and maintainable styling by allowing the easy application of responsive styles based on screen width.

To use the responsive mixin, include it in your SCSS code and customize it according to your project's breakpoints. Here's an example:

```scss
// Usage examples
.my-element {
  width: 100%;

  // Apply styles when the screen width is 600px or more
  @include responsive-mixins.responsive-min(600px) {
 /* Your responsive styles for min-width: 600px */
  }

  // Apply styles when the screen width is up to 600px
  @include responsive-mixins.responsive-max(600px) {
 /* Your responsive styles for max-width: 600px */
  }

  // Apply styles when the screen width is between 600px and 1200px
  @include responsive-mixins.responsive-range(600px, 1200px) {
 /* Your responsive styles for a range of widths */
  }
}
```

</details>

<details closed>
<summary><b>Mobile Only Mixins</b></summary>

This SCSS file `assets/scss/abstracts/_mobile-only-mixins.scss` contains a mixin called `mobile-only` designed to visually hide elements for accessibility (a11y) while making them visible on mobile devices.

Include the `mobile-only` mixin in your SCSS file where you want to hide elements on desktop but make them visible on mobile:

```scss
// Example usage:
.my-element {
  @include mobile-only-mixins.mobile-only;
}
```

</details>

## Stylelint Configuration

[🔝 Back to Top](#wds-bt)

This theme uses a modern `stylelint.config.js` configuration that extends the [WordPress Stylelint Config](https://www.npmjs.com/package/@wordpress/stylelint-config) with additional custom rules to maintain code consistency and enforce best practices.

### Extending WordPress Stylelint Rules

The configuration extends the base WordPress stylelint ruleset, ensuring that all SCSS follows the WordPress coding standards while incorporating additional theme-specific preferences and PHP 8.3 compatibility.

### Running Stylelint

To check your SCSS files for linting errors, run:

```bash
npm run lint:css
```

<details closed>
<summary><b>Custom Rules</b></summary>

- **`declaration-no-important: true`**
  Prohibits the use of `!important` to maintain specificity control.

- **`no-descending-specificity: null`**
  Allows selectors with descending specificity to prevent conflicts with deeply nested components.

- **`selector-class-pattern: null`**
  Disables restrictions on class naming conventions to support custom project structures.

- **`at-rule-no-unknown`**
  Allows SCSS directives and WordPress-specific at-rules:
  - `apply`, `layer`, `variants`, `responsive`, `screen`
  - `use`, `include`, `each`, `if`, `else`, `for`, `while`
  - `function`, `return`, `mixin`, `content`, `extend`
  - `warn`, `error`, `debug`

- **`declaration-property-unit-allowed-list`**
  Restricts certain CSS properties to specific units:
  - `font-size`: `em`, `rem`
  - `line-height`: No units (unitless for better scaling)
  - `border`: Only `px` allowed
  - `margin`: `em`, `rem`
  - `padding`: `em`, `rem`

- **`no-invalid-double-slash-comments: null`**
  Allows SCSS-style double-slash comments (`//`).

- **`comment-no-empty: null`**
  Allows empty comments for documentation purposes.

</details>

## PHP Linting Configuration

[🔝 Back to Top](#wds-bt)

This theme uses PHP_CodeSniffer with WordPress coding standards and PHP 8.3 compatibility checks.

### PHP Compatibility

- **PHP Version**: Fully tested with PHP 8.2+ and PHP 8.3
- **WordPress Standards**: Follows WordPress-Extra and WordPress-Docs coding standards
- **Compatibility**: Uses PHPCompatibilityWP for version-specific checks

### Running PHP Linting

To check your PHP files for coding standard violations, run:

```bash
npm run lint:php
```

<details closed>
<summary><b>PHP Configuration</b></summary>

- **Configuration File**: `phpcs.xml.dist`
- **Test Version**: PHP 8.2-8.3 compatibility
- **Standards**: WordPress-Extra, WordPress-Docs
- **Custom Rules**:
  - Allows array short syntax
  - Allows short prefixes for theme-specific functions
  - Excludes deprecated sniffs for compatibility
  - Theme-specific prefix validation: `WebDevStudios\wdsbt`, `wds`, `wdsbt`
  - Text domain validation: `wdsbt`

</details>

## JavaScript Linting Configuration

[🔝 Back to Top](#wds-bt)

This theme uses ESLint with WordPress coding standards for JavaScript files.

### ESLint Setup

- **Configuration**: Uses `.eslintrc.json` format for WordPress compatibility
- **Standards**: WordPress ESLint plugin with recommended rules
- **Version**: ESLint 8.x for full WordPress tooling compatibility

### Running JavaScript Linting

To check your JavaScript files for coding standard violations, run:

```bash
npm run lint:js
```

<details closed>
<summary><b>JavaScript Configuration</b></summary>

- **Configuration File**: `.eslintrc.json`
- **Standards**: WordPress ESLint plugin recommended rules
- **Special Handling**: Webpack configuration files use Node.js environment
- **Compatibility**: Optimized for WordPress block editor development

</details>

## Dynamic Block Pattern Categories

[🔝 Back to Top](#wds-bt)

This theme automatically registers block pattern categories based on subfolders in the `patterns/` directory. To add a new pattern category:

1. Create a new subfolder inside `patterns/` (e.g., `patterns/cards/`).
2. Place your pattern PHP files in that subfolder.
3. In each pattern file, set the `Categories` header to the folder name (e.g., `Categories: cards`).

The category will be registered automatically and available in the block editor. No manual code changes are needed to add new pattern categories.

## Accessibility, Code Quality, and Security Checks

[🔝 Back to Top](#wds-bt)

WDS BT integrates automated workflow actions to maintain high standards of code security, quality, and accessibility. Accessibility checks are built into the development process, ensuring that websites developed with WDS BT comply with WCAG 2.2 standards. This proactive approach reflects WDS BT's commitment to inclusivity and usability for all users.

<details closed>
<summary><b>Accessibility Test (npm run a11y)</b></summary>

- **Purpose**: To ensure compliance with Web Content Accessibility Guidelines (WCAG).
- **Running the A11y Test Script**:
  - Accessibility tests utilize `pa11y-ci`, which can be run using:

  ```bash
  npm run a11y
  ```

- **How It Works**:
  - **URL Prompt**: The script prompts you for a site URL. Leaving it blank defaults to <https://wdsbt.local>.
  - **Sitemap Detection**: It checks for a sitemap at `[URL]/wp-sitemap.xml`.
  - **Running Tests**:
    - If a sitemap is found, `pa11y-ci` runs on each sub-sitemap.
    - If no sitemap is found, `pa11y-ci` runs on the main page.

  **Example Output:**

  ```bash
  $ npm run a11y
  Please enter the URL to test for accessibility (leave blank to use your local: https://wdsbt.local):
  > https://example.com
  Sitemap found at https://example.com/wp-sitemap.xml. Running pa11y-ci on the sitemap...
  [output from pa11y-ci]
  ```

  If no sitemap is found:

  ```bash
  $ npm run a11y
  Please enter the URL to test for accessibility (leave blank to use your local: https://wdsbt.local):
  > https://example.com
  No sitemap found at https://example.com/wp-sitemap.xml. Running pa11y-ci on the main page...
  [output from pa11y-ci]
  ```

- **Reporting**: Any accessibility violations are displayed in the console for immediate review.

</details>

<details closed>
<summary><b>Security Check (security.yml)</b></summary>

- **Purpose**: Detect vulnerabilities in dependencies.
- **Implementation**: Uses `symfonycorp/security-checker-action@v5` for security scans.
- **Concurrency**: Ensures only one check runs per branch at a time, canceling previous runs.
- **Execution Conditions**:
  - Runs unless:
    - It is a scheduled event (`schedule`), and
    - The repository owner is "webdevstudios."

</details>

<details closed>
<summary><b>Code Quality Check (assertions.yml)</b></summary>

- **Purpose**: Enforce adherence to WordPress coding standards.
- **Configuration**: Quality checks run based on `assertions.yml`.
- **Requirement**: All detected violations must be fixed before commits are accepted.
- **Report Visibility**: Reports appear in the command-line interface (CLI) during checks.

</details>

## Strict Lefthook Integration

[🔝 Back to Top](#wds-bt)

WDS-BT enforces strict Lefthook integration with pre-commit, pre-receive, pre-push, and push hooks. These ensure that all automated quality checks (linting, formatting, security, accessibility) are executed before commits and pushes.

- **Pre-Commit Hook**: Runs quality checks before allowing a commit.
- **Pre-Receive Hook**: Ensures compliance before WDS-BT accepts the push.
- **Pre-Push Hook**: Runs additional validations before pushing changes to remote.
- **Push Hook**: Enforces project-wide integrity checks before finalizing a push.

Bypassing Lefthook (`--no-verify`) is strictly prohibited, ensuring that all enforced checks are properly executed.

<details closed>
<summary><b>Integration Process with LeftHook</b></summary>

1. **Commit Changes**: Modify code as needed.
2. **Automated Checks on Commit**: LeftHook triggers accessibility and code quality checks automatically.
3. **Review Reports**: Examine any violations or issues reported.
4. **Fix Issues**: Resolve identified problems before proceeding.
5. **Recommit**: Once issues are fixed, recommit changes.
6. **Passing Commit**: Commits must pass all checks before acceptance.
7. **Create a Pull Request (PR)**: When creating a PR, checks run on the PR branch.
8. **Review PR Checks**: Ensure all checks pass before merging.

</details>

## Cross-Platform Compatibility

[🔝 Back to Top](#wds-bt)

This project uses [rimraf](https://www.npmjs.com/package/rimraf) in npm scripts instead of `rm -rf` to ensure compatibility across Windows, macOS, and Linux. All contributors can use the provided npm scripts without needing Git Bash or WSL on Windows.

If you add new scripts that need to remove files or directories, please use `rimraf` instead of `rm -rf`.

## Contributing and Support

Your contributions and [support tickets](https://github.com/WebDevStudios/wds-bt/issues) are welcome. Please see our [contributing guidelines](https://github.com/WebDevStudios/wds-bt/blob/main/CONTRIBUTING.md) before submitting a pull request.

WDS BT is free software, and is released under the terms of the GNU General Public License version 2 or any later version. See [LICENSE.md](https://github.com/WebDevStudios/wds-bt/blob/main/LICENSE.md) for complete license.

***

## Acknowledgements

The WDS-BT theme was initially inspired by the [Powder](https://github.com/bgardner/powder) theme. We acknowledge and thank the creators of Powder for their work.

***

[🔝 Back to Top](#wds-bt)
