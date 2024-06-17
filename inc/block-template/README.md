# WDS Block Template

This template is configured to generate a block that is ready for block registration using the [`@wordpress/create-block`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/) tool.

## Usage

Run the following in the terminal of your choice:

`npx @wordpress/create-block --template ../../inc/block-template  --no-plugin`


## Structure

Once the command has completed, the following structure will be created:

``` text
📁src
└── 📁blocks
    └── 📁{example-block}
        └── block.json
        └── edit.js
        └── editor.scss
        └── index.js
        └── render.php
        └── style.scss
```
