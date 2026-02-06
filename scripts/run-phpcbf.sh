#!/bin/bash
# Run phpcbf. Suppress PHP extension warnings only when not in CI.

PHP_BIN=$(./scripts/get-php.sh)
PHP_FLAGS=$(./scripts/get-php-flags.sh)

if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	$PHP_BIN $PHP_FLAGS vendor/bin/phpcbf "$@"
else
	$PHP_BIN $PHP_FLAGS vendor/bin/phpcbf "$@" 2>&1 | grep -vE '(Failed loading|Xdebug requires|Warning: PHP Startup|Unable to load dynamic library)' || exit ${PIPESTATUS[0]}
fi
