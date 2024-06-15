# WDS BT

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png)](https://webdevstudios.com/contact/)

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Code Quality](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml)
[![Security](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/security.yml)


<details>
    <summary><b>Table of Contents</b></summary>
    <a name="back-to-top"></a>

- [Overview](#overview)
- [Features](#features)
- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Development](#development)
    - [NPM Scripts](#npm-scripts)
- [Customizations](#customizations)
        - [Implementation](#implementation)
    - [Mixins](#mixins)
    - [Stylelint Configuration](#stylelint-configuration)
        - [Extends](#extends)
- [Accessibility and Code Quality and Security Checks](#accessibility-and-code-quality-and-security-checks)
- [Contributing and Support](#contributing-and-support)

</details>

***

## Overview
Meet WDS BT, a stylish block theme, tailored for WordPress, featuring native blocks and site editor compatibility. Boasting a contemporary aesthetic, an intuitive interface, and seamless WordPress block editor integration, WDS BT ensures a polished and adaptable framework across all devices. It's crucial to understand that WDS BT is crafted as a foundational theme rather than a parent theme. This difference affords users a flexible starting point for customization. Elevate your website with WDS BT, where design effortlessly meets functionality, providing the ideal canvas for your creative expression.

## Features

| Feature                                          | Description                                                                                         |
|--------------------------------------------------|-----------------------------------------------------------------------------------------------------|
| Native Block Support                             | Stylish block theme tailored for WordPress with support for native blocks and site editor.          |
| Contemporary Aesthetic                           | Boasts a contemporary aesthetic design to enhance the visual appeal of your website.                |
| Intuitive Interface                              | Offers an intuitive interface for easy navigation and seamless user experience.                      |
| Seamless Integration with Block Editor           | Integrates seamlessly with the WordPress block editor for efficient content creation and editing.     |
| Foundation Theme, not Parent Theme               | Crafted as a foundational theme rather than a parent theme, providing flexibility for customization. |
| Polished and Adaptable Framework                 | Ensures a polished and adaptable framework across all devices, enhancing responsiveness and usability. |
| Responsive Design                                | Responsive design ensures optimal viewing experience across various screen sizes and devices.        |
| Flexible Starting Point for Customization        | Provides a flexible starting point for customization, allowing users to tailor the theme to their needs. |
| Automated Workflow Actions for Code Quality      | Automated workflow actions to verify code quality adherence using WordPress coding standards.        |
| Contribution and Support                        | Welcomes contributions and support tickets from users, with detailed guidelines for submitting pull requests. |
| Free Software with GPL License                  | Released under the terms of the GNU General Public License version 2 or any later version, ensuring freedom and openness. |

[🔝 Back to Top](#wds-bt)
***

## Requirements

- WordPress 6.4+
- PHP 8.2+
- [NPM](https://npmjs.com) (v10+)
- [Node](https://nodejs.org) (v20+)
- [Composer](https://getcomposer.org/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)


***
## Getting Started

1. Set up a local WordPress development environment, we recommend using [Local](https://localwp.com/).
2. Ensure you are using WordPress 6.4+.
3. Clone / download this repository into the `/wp-content/themes/` directory of your new WordPress instance.
4. In the WordPress admin, use the Appearance > Themes screen to activate the theme.

[🔝 Back to Top](#wds-bt)
***

## Development

<details closed>
 <summary><b>Theme Structure</b></summary>
 <pre>
  <code>
    └── wds=bt/
        ├── CONTRIBUTING.md
        ├── README.md
        ├── a11y.cjs
        ├── assets
        │   ├── fonts
        │   ├── images
        │   ├── index.js
        │   ├── js
        │   │   ├── block-filters
        │   │   │   ├── index.js
        │   │   │   └── unregister-core-embed.js
        │   │   ├── block-variations
        │   │   │   └── index.js
        │   │   ├── global
        │   │   │   ├── header.js
        │   │   │   ├── index.js
        │   │   │   └── table.js
        │   │   ├── index.js
        │   │   └── templates
        │   │       └── index.js
        │   └── scss
        │       ├── abstracts
        │       │   ├── _abstracts.scss
        │       │   ├── _mobile-only-mixins.scss
        │       │   ├── _responsive-mixins.scss
        │       │   └── _utility.scss
        │       ├── base
        │       │   ├── _base.scss
        │       │   ├── _global.scss
        │       │   └── _pagination.scss
        │       ├── blocks
        │       │   └── core
        │       │       ├── ...
        │       │   └── custom
        │       │       ├── _custom.scss
        │       ├── components
        │       │   ├── _components.scss
        │       │   └── _forms.scss
        │       ├── index.scss
        │       ├── layout
        │       │   ├── _footer.scss
        │       │   ├── _header.scss
        │       │   └── _layout.scss
        │       └── pages
        │           ├── _404.scss
        │           ├── _archive.scss
        │           ├── _pages.scss
        │           └── _search.scss
        ├── composer.json
        ├── composer.lock
        ├── functions.php
        ├── inc
        │   ├── functions
        │   │   └── security.php
        │   ├── hooks
        │   │   ├── enable-svg.php
        │   │   ├── enqueue-block-stylesheet.php
        │   │   ├── register-block-categories.php
        │   │   ├── register-block-pattern-categories.php
        │   │   ├── register-block-styles.php
        │   │   ├── register-block-variations.php
        │   │   ├── remove-archive-title-prefix.php
        │   │   └── unregister-block-variations.php
        │   └── setup
        │       ├── preload-scripts.php
        │       ├── scripts.php
        │       └── setup.php
        ├── lefthook.yml
        ├── package-lock.json
        ├── package.json
        ├── parts
        │   ├── footer.html
        │   └── header.html
        ├── patterns
        │   ├── footer-default.php
        │   └── header-default.php
        ├── phpcs.xml.dist
        ├── postcss.config.js
        ├── readme.txt
        ├── screenshot.png
        ├── style.css
        ├── styles
        │   └── dark.json
        ├── templates
        │   ├── 404.html
        │   ├── archive.html
        │   ├── index.html
        │   ├── page-blank.html
        │   ├── page-no-title.html
        │   ├── page.html
        │   ├── search.html
        │   └── single.html
        ├── theme.json
        ├── webpack.config.js
        └── webpack.prod.js
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

|  | Command | Description |
|-------|---------|-------------|
| 🌐 | `npm run a11y` | Run accessibility tests using Pa11y-CI. |
| 🛠️ | `npm run build` | Build the theme using `wp-scripts`. |
| 📝 | `npm run format` | Format files using `wp-scripts` and `composer`. |
| 🔍 | `npm run lint` | Run all linting scripts. |
| 🎨 | `npm run lint:css` | Lint CSS files using `wp-scripts`. |
| 🚀 | `npm run lint:js` | Lint JavaScript files using `wp-scripts`. |
| 📚 | `npm run lint:md:docs` | Lint Markdown files in the `docs` directory using `wp-scripts`. |
| 🐘 | `npm run lint:php` | Lint PHP files using `composer`. |
| 📦 | `npm run lint:pkg-json` | Lint `package.json` and `composer.json` using `wp-scripts`. |
| 🔄 | `npm run packages-update` | Update dependencies defined in `package.json` using `wp-scripts`. |
| 🔄 | `npm run reset` | Remove `node_modules`, `vendor`, `build`, `package-lock.json`, and `composer.lock` files. |
| 🛠️ | `npm run setup` | Reset, install dependencies, and build the theme. |
| ▶️ | `npm run start` | Start the development server using `wp-scripts`. |


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
<summary><b>Overriding/Customizing Block Styles</b></summary>

1. Navigate to the `assets/scss/blocks/` directory within your theme. If overriding a core block style, use the `core` folder, if overriding a block from a plugin use the `custom` folder.

2. Create an SCSS file with the exact filename as the block name you want to customize. This file will house your custom styles for that specific block.

3. Files within the `assets/scss/blocks/` directory are automatically enqueued, simplifying the integration of your custom styles into the WordPress block editor. Do not import these files into the main `index.scss`

4. After adding your custom SCSS file, run the following command to compile and apply your changes:

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

> **_NOTE:_** To find the correct block name, open the block editor, launch the browser console and type `wp.blocks.getBlockTypes()`. You will see the complete list of block names (from core or third-party).

</details>

<details closed>
<summary><b>Unregister Blocks and Variations</b></summary>

This functionality allows you to unregister and disable specific core Gutenberg blocks, styles, and variations that are not needed on your WordPress website. By removing these unused blocks and variations, you can streamline the Gutenberg editor and improve the overall performance of your site.

#### Implementation

The script in `assets/js/block-filters/unregister-core-embed.js` loops through a list of unused blocks and variations, unregistering them from the Gutenberg editor. Additionally, it keeps only the specified embed variations for the core/embed block.

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

This SCSS file `assets/scss/abstracts/_responsive-mixins.scss` provides mixins for creating responsive media queries with both minimum and maximum width conditions. The file promotes modular and maintainable styling by allowing the easy application of responsive styles based on screen width.

To use the responsive mixin, include it in your SCSS code and customize it according to your project's breakpoints. Here's an example:

```scss
// Usage examples
.my-element {
  width: 100%;

  // Apply styles when the screen width is 600px or more
  @include responsive-min(600px) {
    /* Your responsive styles for min-width: 600px */
  }

  // Apply styles when the screen width is up to 600px
  @include responsive-max(600px) {
    /* Your responsive styles for max-width: 600px */
  }

  // Apply styles when the screen width is between 600px and 1200px
  @include responsive-min(600px) and (max-width: 1200px) {
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
  @include mobile-only;
}
```
</details>

### Stylelint Configuration

#### Extends

The configuration extends two base configurations:

- @wordpress/stylelint-config/scss: This extends the WordPress SCSS stylelint configuration.
- stylelint-config-prettier: This extends the Prettier stylelint configuration.

These base configurations provide a foundation for enforcing consistent styles and conventions in SCSS files.

<details closed>
<summary><b>Rules</b></summary>

- `declaration-no-important`: Disallows the use of !important in declarations.
- `scss/at-rule-no-unknown`: Allows specific SCSS at-rules to be ignored, such as @apply, @layer, @variants, @responsive, and @screen.
- `string-quotes`: Enforces the use of single quotes for strings.
- `declaration-property-unit-allowed-list`: Specifies allowed units for the following properties:
  - font-size: allows only `em` and `rem`
  - line-height: unitless
  - border: allow only `px`
  - margin: allows only `em` and `rem`
  - padding: allows only `em` and `rem`

</details>

[🔝 Back to Top](#wds-bt)
***

## Accessibility and Code Quality and Security Checks

WDS BT is equipped with automated workflow actions that ensure code security and uphold code quality standards with every commit. Accessibility check has been integrated into the development process, guaranteeing that websites built with WDS BT prioritize accessibility compliance based on WCAG 2.2 standards. This proactive approach underscores WDS BT's commitment to providing an inclusive user experience for all.

<details closed>
<summary><b>A11y Test (npm run a11y)</b></summary>

- **Purpose**: To guarantee that the theme meets the Web Content Accessibility Guidelines (WCAG) standards.
- **Running the A11y Test Script**:
  - Accessibility checks are integrated into the development process using `pa11y-ci` by running:
    ```bash
        npm run a11y
    ```
  - You will be prompted to enter the URL of the site you want to test for accessibility. If you leave it blank, it will default to https://wdsbt.local.
- **Script Details**:
  The script performs the following actions
  - **Prompts for URL**: You will be prompted to enter the URL of the site you want to test for accessibility. If you leave it blank, it will default to https://wdsbt.local.
  - **Checks for Sitemap**: Attempts to access the sitemap at `[URL]/wp-sitemap.xml`.
  - **Runs Accessibility Tests**:
    - If the sitemap is found, the script runs `pa11y-ci` on the sitemap URL.
      ```bash
        $ npm run a11y
        Please enter the URL to test for accessibility (leave blank to use your local: https://wdsbt.local):
        > https://example.com
        Sitemap found at https://example.com/wp-sitemap.xml. Running pa11y-ci on the sitemap...
        [output from pa11y-ci]
      ```
    - If the sitemap is not found, the script runs pa11y-ci on the main page URL.
      ```bash
        $ npm run a11y
        Please enter the URL to test for accessibility (leave blank to use your local: https://wdsbt.local):
        > https://example.com
        No sitemap found at https://example.com/wp-sitemap.xml. Running pa11y-ci on the main page...
        [output from pa11y-ci]
      ```
- **Violation Reports**: Any detected accessibility violations are displayed in the console for immediate review and action.

</details>

<details closed>
<summary><b>Security Check (security.yml)</b></summary>

- **Purpose**: Perform security checks on dependencies to identify vulnerabilities.
- **Configuration**: Uses `symfonycorp/security-checker-action@v5` for security scanning.
- **Concurrency Handling**: Ensures only one instance runs per branch concurrently, cancelling previous runs.
- **Job**:
  - **Name**: Security check
  - **OS**: Ubuntu latest
- **Conditional Execution**:
  - Runs only if:
    - Not a scheduled event (`schedule`), or
    - Scheduled event, but repository owner is not "webdevstudios."

</details>

<details closed>
<summary><b>Code Quality Check (assertions.yml)</b></summary>

- **Purpose**: To verify code quality adherence using WordPress coding standards.
- **Configuration**: The code quality check is performed using predefined assertions in `assertions.yml`.
- **Action Requirement**: All detected code issues and violations must be addressed and fixed before any commit can successfully pass through.
- **Report Display**: The assertions report will appear in the command-line interface (CLI) during the checks.

</details>

<details closed>
<summary><b>Integration Process with LeftHook</b></summary>

1. **Commit Changes**: Make your changes to the codebase as usual.
2. **Automated Checks on Commit**: LeftHook triggers automated checks upon each commit.
3. **Review Reports**: Check the generated reports for any accessibility violations or code quality issues.
4. **Address Issues**: Address and fix any identified violations or issues.
5. **Recommit**: Once all issues are resolved, recommit your changes.
6. **Passing Commit**: Your commit will successfully pass through once all checks are clear.
7. **Create Pull Request (PR)**: When you create a PR, the actions are triggered again to run on the PR branch.
8. **Review PR Checks**: Review the checks on the PR to ensure compliance before merging.

</details>

***

## Contributing and Support

Your contributions and [support tickets](https://github.com/WebDevStudios/wds-bt/issues) are welcome. Please see our [contributing guidelines](https://github.com/WebDevStudios/wds-bt/blob/main/CONTRIBUTING.md) before submitting a pull request.

WDS BT is free software, and is released under the terms of the GNU General Public License version 2 or any later version. See [LICENSE.md](https://github.com/WebDevStudios/wds-bt/blob/main/LICENSE.md) for complete license.

***

## Acknowledgements

The WDS-BT theme was initially inspired by the [Powder](https://github.com/bgardner/powder) theme. We acknowledge and thank the creators of Powder for their work.

***

[🔝 Back to Top](#wds-bt)
