#!/bin/bash
# Run phpcbf. Suppress PHP extension warnings only when not in CI.
# PHP is provided by DevContainer or host (native).

if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	vendor/bin/phpcbf "$@"
else
	vendor/bin/phpcbf "$@" 2>&1 | grep -vE '(Failed loading|Xdebug requires|Warning: PHP Startup|Unable to load dynamic library)' || exit ${PIPESTATUS[0]}
fi
