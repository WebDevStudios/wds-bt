# Linting & Lefthook

## Strict Linting

- **PHP (PHPCS)**: WordPress-Extra and WordPress-Docs; PHP 8.2–8.3; warnings treated as failures (`--runtime-set ignore_warnings_on_exit 0`). Config: `phpcs.xml.dist`. Run: `npm run lint:php`.
- **JavaScript (ESLint)**: WordPress recommended rules; ESLint 9 flat config (`eslint.config.cjs`); `--max-warnings 0`. Run: `npm run lint:js`.
- **CSS/SCSS (Stylelint)**: Extends `@wordpress/stylelint-config`; `--max-warnings 0`. Run: `npm run lint:css`.

## Fixes on Commit

On each commit, Lefthook runs **auto-fix** (PHPCBF, Prettier, Stylelint `--fix`, ESLint `--fix`), **re-stages** any changed files, then runs the **strict linters**. If anything still fails, the commit is blocked.

## No-verify Policy

Do **not** use `git commit --no-verify` or `git push --no-verify`. CI runs the same strict checks on every PR; commits that skip hooks will still be rejected when the PR is checked.

## Stylelint Details

- Extends [WordPress Stylelint Config](https://www.npmjs.com/package/@wordpress/stylelint-config).
- Custom rules: `declaration-no-important: true`, SCSS at-rules allowed, unit allowlists for font-size, margin, padding, etc. See `stylelint.config.js`.

## PHP Linting Details

- Config: `phpcs.xml.dist`. Theme prefixes: `WebDevStudios\wdsbt`, `wds`, `wdsbt`. Text domain: `wdsbt`.
- PHP path and extension flags are auto-detected (`scripts/get-php.sh`, `scripts/get-php-flags.sh`).

## JavaScript Linting Details

- Config: `eslint.config.cjs` (ESLint 9 flat config). WordPress plugin via `@eslint/eslintrc` compatibility layer. Webpack config uses Node environment.

## Lefthook Integration

Pre-commit runs format + lint fix + strict lint per file type (PHP, CSS/SCSS, JS/TS/TSX). Merge-conflict and exit-status checks run as well. See `lefthook.yml`.
