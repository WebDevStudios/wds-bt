# WDS Block Template

This template is configured to generate a block that is ready for block registration using the [`@wordpress/create-block`](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-create-block/) tool.

## Usage

Run the following in the terminal of your choice:

`npm run create-block`


## Structure

Once the command has completed, the following structure will be created:

``` text
📁blocks
  └── 📁{example-block}
        └── block.json
        └── edit.js
        └── editor.scss
        └── index.js
        └── render.php
        └── view.js
        └── style.scss
```
