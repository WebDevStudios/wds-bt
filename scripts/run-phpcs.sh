#!/bin/bash
# Run phpcs. Suppress PHP extension warnings only when not in CI.
# PHP is provided by DevContainer or host (native).

PHPCS_STRICT="--runtime-set ignore_warnings_on_exit 0"
if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	vendor/bin/phpcs $PHPCS_STRICT "$@"
else
	vendor/bin/phpcs $PHPCS_STRICT "$@" 2>&1 | grep -vE '(Failed loading|Xdebug requires|Warning: PHP Startup|Unable to load dynamic library)' || exit ${PIPESTATUS[0]}
fi
