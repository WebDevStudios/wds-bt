# WDS BT <!-- omit in toc -->

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2024/02/wds-banner.png)](https://webdevstudios.com/contact/)

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![PHPCS CHECKS](https://github.com/WebDevStudios/wds-bt/actions/workflows/phpcs.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/phpcs.yml)
[![CODE QUALITY](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml/badge.svg)](https://github.com/WebDevStudios/wds-bt/actions/workflows/assertions.yml)

Meet WDS BT, a stylish block theme, tailored for WordPress, featuring native blocks and site editor compatibility. Boasting a contemporary aesthetic, an intuitive interface, and seamless WordPress block editor integration, WDS BT ensures a polished and adaptable framework across all devices. It's crucial to understand that WDS BT is crafted as a foundational theme rather than a parent theme. This difference affords users a flexible starting point for customization. Elevate your website with WDS BT, where design effortlessly meets functionality, providing the ideal canvas for your creative expression.

***

## Table of Contents

- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Setup](#setup)
- [Development](#development)
  - [Registering Block Styles](#registering-block-styles)
  - [Overriding/Customizing Core Block Styles](#overridingcustomizing-core-block-styles)
  - [Creating Block Variations](#creating-block-variations)
- [Mixins](#mixins)
  - [Responsive Mixins](#responsive-mixins)
  - [Mobile Only Mixins](#mobile-only-mixins)
- [Stylelint Configuration](#stylelint-configuration)
- [Theme Unit Test](#theme-unit-test)
- [Contributing and Support](#contributing-and-support)

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

***

## Setup

From the command line, change directories to your new theme directory:

```bash
cd /wp-content/themes/your-theme
```

Install theme dependencies and trigger an initial build.

>Note: You will need to have Composer 2 and NPM 10 installed first.

```bash
npm i && composer i
```

***

## Development

From the command line, type any of the following to perform an action:

Command | Action
:- | :-
`npm run a11y` | Builds production-ready assets for a deployment
`npm run build` | Builds production-ready assets for a deployment
`npm run format` | Fix all CSS, JS, MD, and PHP formatting errors automatically
`npm run lint` | Check all CSS, JS, MD, and PHP files for errors
`npm run report` | Gives detailed information on coding standards violations in PHP code
`npm run start` | Builds assets and starts Live Reload server

***

## Registering Block Styles

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

***

## Overriding/Customizing Core Block Styles

1. Navigate to the `assets/scss/blocks/core` directory within your theme.

2. Create an SCSS file with the exact filename as the block name you want to customize. This file will house your custom styles for that specific core block.

3. Files within the `assets/scss/blocks/core` directory are automatically enqueued, simplifying the integration of your custom styles into the WordPress block editor.

4. After adding your custom SCSS file, run the following command to compile and apply your changes:

    ```bash
    npm run build
    ```

***

## Creating Block Variations

1. In the `assets/js/variations` directory within your theme, create a new JavaScript file. This file will contain the definition of your block variation.

2. Import the newly created file into the `assets/js/variations/index.js` file. This step ensures that your variation is included in the build process.

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

***

## Mixins

### Responsive Mixins

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

### Mobile Only Mixins

This SCSS file `assets/scss/abstracts/_mobile-only-mixins.scss` contains a mixin called `mobile-only` designed to visually hide elements for accessibility (a11y) while making them visible on mobile devices.

Include the `mobile-only` mixin in your SCSS file where you want to hide elements on desktop but make them visible on mobile:

```scss
// Example usage:
.my-element {
  @include mobile-only;
}
```

***

## Stylelint Configuration

### Extends

The configuration extends two base configurations:

- @wordpress/stylelint-config/scss: This extends the WordPress SCSS stylelint configuration.
- stylelint-config-prettier: This extends the Prettier stylelint configuration.

These base configurations provide a foundation for enforcing consistent styles and conventions in SCSS files.

### Rules

- `declaration-no-important`: Disallows the use of !important in declarations.
- `scss/at-rule-no-unknown`: Allows specific SCSS at-rules to be ignored, such as @apply, @layer, @variants, @responsive, and @screen.
- `string-quotes`: Enforces the use of single quotes for strings.
- `declaration-property-unit-allowed-list`: Specifies allowed units for the following properties:
  - font-size: allows only `em` and `rem`
  - line-height: unitless
  - border: allow only `px`
  - margin: allows only `em` and `rem`
  - padding: allows only `em` and `rem`

***

## Accessibility and Code Quality Checks

WDS BT is equipped with automated workflow actions that ensure accessibility compliance and uphold code quality standards with every commit. Here's what you need to know to ensure smooth integration and contribution:

### Accessibility Check (a11y.yml)

- **Purpose**: To guarantee accessibility compliance based on WCAG 2.2 standards.
- **Configuration**: The accessibility check is performed using `pa11y-ci` with a custom configuration in `a11y.yml`.
- **Local URL Configuration**:
  - Before running the accessibility check, ensure the local URL is correctly set in `.pa11yci`.
  - Replace the `"urls"` array with the appropriate local URL of your environment.
- **Violation Reports**: Any accessibility violations detected will be saved in the `pa11y-ci-report` folder for further analysis.

### Code Quality Check (assertions.yml)

- **Purpose**: To verify code quality adherence using WordPress coding standards.
- **Configuration**: The code quality check is performed using predefined assertions in `assertions.yml`.
- **Action Requirement**: All detected code issues and violations must be addressed and fixed before any commit can successfully pass through.
- **Report Display**: The assertions report will appear in the command-line interface (CLI) during the checks.

### Integration Process with LeftHook

1. **Commit Changes**: Make your changes to the codebase as usual.
2. **Automated Checks on Commit**: LeftHook triggers automated checks upon each commit.
3. **Review Reports**: Check the generated reports for any accessibility violations or code quality issues.
4. **Address Issues**: Address and fix any identified violations or issues.
5. **Recommit**: Once all issues are resolved, recommit your changes.
6. **Passing Commit**: Your commit will successfully pass through once all checks are clear.
7. **Create Pull Request (PR)**: When you create a PR, the actions are triggered again to run on the PR branch.
8. **Review PR Checks**: Review the checks on the PR to ensure compliance before merging.

#### Note

- Regularly monitor the accessibility and code quality reports to ensure ongoing compliance and maintain high standards within the project.
- Collaborate with team members to address any detected issues promptly, fostering a culture of accessibility and code excellence.

***

## Theme Unit Test

1. Download the theme test data from <https://github.com/WebDevStudios/wds-bt/blob/main/wdsunittestdata.wordpress.xml>
2. Import test data into your WordPress install by going to Tools => Import => WordPress
3. Select the XML file from your computer
4. Click on “Upload file and import”.
5. Under “Import Attachments,” check the “Download and import file attachments” box and click submit.

*Note: You may have to repeat the Import step until you see “All Done” to obtain the full list of Posts and Media.*

***

## Contributing and Support

Your contributions and [support tickets](https://github.com/WebDevStudios/wds-bt/issues) are welcome. Please see our [contributing guidelines](https://github.com/WebDevStudios/wds-bt/blob/main/CONTRIBUTING.md) before submitting a pull request.

WDS BT is free software, and is released under the terms of the GNU General Public License version 2 or any later version. See [LICENSE.md](https://github.com/WebDevStudios/wds-bt/blob/main/LICENSE.md) for complete license.
