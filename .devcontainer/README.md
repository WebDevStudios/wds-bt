# Theme Dev Container

A **theme-only** dev container so you can work on WDS BT without installing PHP or Node locally—whether you’re at WDS (using the project template) or anywhere else. The stack matches [WebDevStudios/wds-devcontainer](https://github.com/WebDevStudios/wds-devcontainer): **PHP 8.2** and **Node 24**.

## Usage

1. Open this theme repo in Cursor or VS Code.
2. Choose **Reopen in Container** (or run **Dev Containers: Reopen in Container** from the command palette).
3. After the container builds, run `npm run setup` if needed (postCreate runs `npm ci && composer install`).

## What’s included

- PHP 8.2 with Composer and extensions: tokenizer, xmlwriter, simplexml, dom, mbstring  
- Node 24  
- VS Code extensions: Intelephense, ESLint, Prettier, Stylelint  

When you use a full WordPress project (e.g. WDS project template), that project’s devcontainer is used for the site; this one is for standalone theme development only.
