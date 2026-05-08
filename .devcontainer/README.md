# Theme Dev Container

This is the **supported development environment** for WDS BT: a theme-only container so you do not need PHP or Node installed on the host. The stack matches [WebDevStudios/wds-devcontainer](https://github.com/WebDevStudios/wds-devcontainer): **PHP 8.2** and **Node 24**.

PHP linting uses Composer scripts that call **`vendor/bin/phpcs`** and **`vendor/bin/phpcbf`** (`composer run-script phpcs` / `phpcs-fix`)—no extra wrapper scripts. Run those inside the container (or any Linux/macOS shell with Composer deps installed) for consistent results.

## Usage

1. Open this theme repo in Cursor or VS Code.
2. Choose **Reopen in Container** (or **Dev Containers: Reopen in Container** from the command palette).
3. After the container builds, `postCreateCommand` runs `npm ci && composer install`. Use `npm run setup` from the theme root when you need a full clean install and build.

## What’s included

- PHP 8.2 with Composer and extensions: tokenizer, xmlwriter, simplexml, dom, mbstring  
- Node 24  
- **jq** (installed on container create) so `composer install` and Cursor-rules sync from `composer.json` succeed  
- VS Code extensions: Intelephense, ESLint, Prettier, Stylelint  

When you use a full WordPress project (e.g. WDS project template), that project’s devcontainer runs the site; this one is for standalone theme development only.
