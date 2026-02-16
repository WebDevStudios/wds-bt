# Accessibility & Quality

## Accessibility (npm run a11y)

Runs `pa11y-ci` for WCAG compliance. Prompts for URL (default https://wdsbt.local). Uses sitemap at `[URL]/wp-sitemap.xml` if present; otherwise tests the main page. Violations are shown in the console.

## Lighthouse (npm run lighthouse)

Runs Lighthouse for Performance, Accessibility, Best Practices, and SEO (mobile and desktop). Scores only in the terminal; no report files. URL via prompt or: `npm run lighthouse -- https://yoursite.local`. See `scripts/README.md` for details.

## Security (security.yml)

Uses `symfonycorp/security-checker-action@v5` to scan dependencies for vulnerabilities. Concurrency limits one run per branch.

## Code Quality (assertions.yml)

Runs PHPCS, ESLint, and Stylelint in CI. All violations must be fixed before merge. Reports appear in the CLI.

## Cross-Platform

- **rimraf** is used instead of `rm -rf` in npm scripts for Windows/macOS/Linux.
- **PHP:** Path and extension flags are auto-detected (`scripts/get-php.sh`, `scripts/get-php-flags.sh`). For CI you can set `PHP_BIN`. Local uses `-n` to suppress extension warnings; CI enables `tokenizer`, `xmlwriter`, `simplexml` as needed.
