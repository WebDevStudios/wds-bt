# WDS BT <!-- omit in toc -->

[![WebDevStudios. Your Success is Our Mission.](https://webdevstudios.com/wp-content/uploads/2018/04/wds-github-banner.png)](https://webdevstudios.com/contact/)

Meet WDS BT, a stylish block theme drawing inspiration from the Powder Theme. With a modern design, user-friendly interface, and smooth integration with the WordPress block editor, WDS BT delivers a clean and responsive layout. It's important to note that this theme is intentionally designed as a base theme, not a parent theme. This distinction offers users a customizable foundation to build upon. Elevate your website with WDS BT, where design effortlessly meets functionality, providing the ideal canvas for your creative expression.

## Table of Contents

- [Requirements](#requirements)
- [Getting Started](#getting-started)
- [Setup](#setup)
- [Development](#development)
  - [Registering Block Styles](#registering-block-styles)
  - [Overriding/Customizing Core Block Styles](#overridingcustomizing-core-block-styles)
  - [Creating Block Variations](#creating-block-variations)
- [Syncing with Upstream Repo](#syncing-with-upstream-repo)
- [Contributing and Support](#contributing-and-support)

## Requirements

- WordPress 6.4+
- PHP 8.2+
- [NPM](https://npmjs.com) (v10+)
- [Node](https://nodejs.org) (v20+)
- [Composer](https://getcomposer.org/)
- License: [GPLv3](https://www.gnu.org/licenses/gpl-3.0.html)

## Getting Started

1. Set up a local WordPress development environment, we recommend using [Local](https://localwp.com/).
2. Ensure you are using WordPress 6.4+.
3. Clone / download this repository into the `/wp-content/themes/` directory of your new WordPress instance.
4. In the WordPress admin, use the Appearance > Themes screen to activate the theme.

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

## Development

From the command line, type any of the following to perform an action:

Command | Action
:- | :-
`npm run start` | Builds assets and starts Live Reload server
`npm run build` | Builds production-ready assets for a deployment
`npm run lint` | Check all CSS, JS, MD, and PHP files for errors
`npm run format` | Fix all CSS, JS, MD, and PHP formatting errors automatically
`npm run report` | Gives detailed information on coding standards violations in PHP code

### Registering Block Styles

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

### Overriding/Customizing Core Block Styles

1. Navigate to the `assets/scss/blocks/core` directory within your theme.

2. Create an SCSS file with the exact filename as the block name you want to customize. This file will house your custom styles for that specific core block.

3. Files within the `assets/scss/blocks/core` directory are automatically enqueued, simplifying the integration of your custom styles into the WordPress block editor.

4. After adding your custom SCSS file, run the following command to compile and apply your changes:

    ```bash
    npm run build
    ```

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

## Responsive Mixins

This SCSS file `assets/scss/abstracts/_responsive-mixins.scss` provides mixins for creating responsive media queries with both minimum and maximum width conditions. The file promotes modular and maintainable styling by allowing the easy application of responsive styles based on screen width.

## Usage

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

## Syncing with Upstream Repo

### Configure a Remote Repository for the Fork

1. Open Terminal.
2. Specify a new remote upstream repository that will be synced with the fork.

``` CODE
git remote add upstream https://github.com/bgardner/wdsbt
```

### Getting Upstream Changes

1. Change the current working directory to your local project.
2. Fetch the branches and their respective commits from the upstream repository. Commits to `main` will be stored in the local branch `upstream/main`.
3. Check out the fork's local default branch `main`.
4. Merge the changes from the upstream default branch - in this case, `upstream/main` - into your local default branch.

``` CODE
git fetch upstream
git checkout main
git merge upstream/main
```

### Theme Unit Test

1. Download the theme test data from <https://github.com/WebDevStudios/wds-bt/blob/main/wdsunittestdata.wordpress.xml>
2. Import test data into your WordPress install by going to Tools => Import => WordPress
3. Select the XML file from your computer
4. Click on “Upload file and import”.
5. Under “Import Attachments,” check the “Download and import file attachments” box and click submit.

*Note: You may have to repeat the Import step until you see “All Done” to obtain the full list of Posts and Media.*

## Contributing and Support

Your contributions and [support tickets](https://github.com/WebDevStudios/wds-bt/issues) are welcome. Please see our [contributing guidelines](https://github.com/WebDevStudios/wds-bt/blob/main/CONTRIBUTING.md) before submitting a pull request.

WDS BT is free software, and is released under the terms of the GNU General Public License version 2 or any later version. See [LICENSE.md](https://github.com/WebDevStudios/wds-bt/blob/main/LICENSE.md) for complete license.
