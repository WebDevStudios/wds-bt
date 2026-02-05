#!/bin/bash
# Run phpcs. Suppress PHP extension warnings only when not in CI.

PHP_BIN=$(./scripts/get-php.sh)
PHP_FLAGS=$(./scripts/get-php-flags.sh)

# Strict: treat warnings as failures (no-verify bypass is not allowed).
PHPCS_STRICT="--runtime-set ignore_warnings_on_exit 0"
if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	$PHP_BIN $PHP_FLAGS vendor/bin/phpcs $PHPCS_STRICT "$@"
else
	$PHP_BIN $PHP_FLAGS vendor/bin/phpcs $PHPCS_STRICT "$@" 2>&1 | grep -vE '(Failed loading|Xdebug requires|Warning: PHP Startup|Unable to load dynamic library)' || exit ${PIPESTATUS[0]}
fi
