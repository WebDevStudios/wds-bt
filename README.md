# WDS BT

## Version: 1.1.0

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png)](https://webdevstudios.com/contact/)

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Code Quality](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml)
[![Security](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml)

<details>
 <summary><b>Table of Contents</b></summary>
 <a name="back-to-top"></a>

- [WDS BT V1.1.0](#wds-bt-v110)
  - [Overview](#overview)
  - [Requirements](#requirements)
  - [Getting Started](#getting-started)
  - [Development](#development)
    - [NPM Scripts](#npm-scripts)
  - [Creating Blocks](#creating-blocks)
  - [Customizations](#customizations)
  - [Implementation](#implementation)
    - [Mixins](#mixins)
    - [Stylelint Configuration](#stylelint-configuration)
      - [Extending WordPress Stylelint Rules](#extending-wordpress-stylelint-rules)
    - [Running Stylelint](#running-stylelint)
  - [Accessibility, Code Quality, and Security Checks](#accessibility-code-quality-and-security-checks)
  - [Strict Lefthook Integration](#strict-lefthook-integration)
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
| Automated Code Quality                           | Workflow actions ensure adherence to WordPress coding standards.                                    |
| Third-party Block Style Overrides                | Conditionally enqueue and override third-party block styles for efficient asset delivery.           |
| Accessibility Compliance                         | Built-in WCAG 2.2 compliance with automated Pa11y checks.                                           |
| Enhanced Webpack Configuration                   | Refined Webpack setup for improved dependency resolution and optimized asset management.            |
| Block Creation Script Enhancements               | Options for static, dynamic, or interactive blocks; automatically includes `view.js` for rendering. |
| LeftHook Integration                             | Required for pre-commit hooks and automated code quality checks.                                           |

[🔝 Back to Top](#wds-bt)
***

## Requirements

- WordPress 6.4+
- PHP 8.2+
- [NPM](https://npmjs.com) (v10+)
- [Node](https://nodejs.org) (v20+)
- [Composer 2+](https://getcomposer.org/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

***

## Getting Started

1. Clone this repository to your WordPress theme directory (`wp-content/themes/`).
2. Activate WDS BT from your WordPress admin panel under Appearance > Themes.
3. Run `npm run setup` to install dependencies and perform an initial build.

[🔝 Back to Top](#wds-bt)
***

## Development

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
   └── query-block-sticky-override.php
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

### NPM Scripts

*From the command line, type any of the following to perform an action:*

|     | Command                     | Description                                                     |
|-----|-----------------------------|-----------------------------------------------------------------|
| 🌐  | `npm run a11y`              | Run accessibility tests (Pa11y-CI).                             |
| 🛠️  | `npm run build`             | Build the theme assets.                                         |
| 🔨  | `npm run create-block`      | Scaffold a new block with various configurations.               |
| 📝  | `npm run format`            | Format all code files (JS, SCSS, PHP).                          |
| 🎨  | `npm run format:css`        | Format SCSS files.                                              |
| 🐘  | `npm run format:php`        | Format PHP files.                                               |
| 🔍  | `npm run lint`              | Run all linting scripts.                                        |
| 🎨  | `npm run lint:css`          | Lint CSS files.                                                 |
| 🚀  | `npm run lint:js`           | Lint JavaScript files.                                          |
| 🐘  | `npm run lint:php`          | Lint PHP files.                                                 |
| 🔄  | `npm run packages-update`   | Update dependencies defined in `package.json`.                  |
| 🛠️  | `npm run setup`             | Reset, install dependencies, and build the theme.               |
| ▶️  | `npm run start`             | Start the development server.                                   |
| 🔖  | `npm run version-update`    | Update the theme version based on environment variable.         |

[🔝 Back to Top](#wds-bt)
***

## Creating Blocks

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

[🔝 Back to Top](#wds-bt)
***

## Customizations

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
   - **Naming Convention:** Name each file using the block’s slug (the part after the namespace). For example, to override the `wdsbt/test` block, name the file `test.scss`.

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

### Mixins

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

### Stylelint Configuration

This theme follows the [WordPress Stylelint Config](https://www.npmjs.com/package/@wordpress/stylelint-config) with additional custom rules to maintain code consistency and enforce best practices.

#### Extending WordPress Stylelint Rules

The configuration extends the base `@wordpress/stylelint-config/scss` ruleset, ensuring that all SCSS follows the WordPress coding standards while incorporating additional theme-specific preferences.

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

- **`scss/at-rule-no-unknown`**
  Allows certain Tailwind-like SCSS directives:
  - `apply`
  - `layer`
  - `variants`
  - `responsive`
  - `screen`

- **`declaration-property-unit-allowed-list`**
  Restricts certain CSS properties to specific units:
  - `font-size`: `em`, `rem`
  - `line-height`: No units (unitless for better scaling)
  - `border`: Only `px` allowed
  - `margin`: `em`, `rem`
  - `padding`: `em`, `rem`

</details>

[🔝 Back to Top](#wds-bt)
***

## Accessibility, Code Quality, and Security Checks

WDS BT integrates automated workflow actions to maintain high standards of code security, quality, and accessibility. Accessibility checks are built into the development process, ensuring that websites developed with WDS BT comply with WCAG 2.2 standards. This proactive approach reflects WDS BT’s commitment to inclusivity and usability for all users.

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

***

[🔝 Back to Top](#wds-bt)

## Strict Lefthook Integration

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

## Contributing and Support

Your contributions and [support tickets](https://github.com/WebDevStudios/wds-bt/issues) are welcome. Please see our [contributing guidelines](https://github.com/WebDevStudios/wds-bt/blob/main/CONTRIBUTING.md) before submitting a pull request.

WDS BT is free software, and is released under the terms of the GNU General Public License version 2 or any later version. See [LICENSE.md](https://github.com/WebDevStudios/wds-bt/blob/main/LICENSE.md) for complete license.

***

## Acknowledgements

The WDS-BT theme was initially inspired by the [Powder](https://github.com/bgardner/powder) theme. We acknowledge and thank the creators of Powder for their work.

***

[🔝 Back to Top](#wds-bt)
