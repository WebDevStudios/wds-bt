#!/bin/bash
# Get PHP flags based on environment.
# In CI/CD, enable extensions. In local dev, suppress extension warnings.

# Check if we're in CI environment.
if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	# CI environment: enable required extensions explicitly.
	printf '%s' "-d extension=tokenizer -d extension=xmlwriter -d extension=simplexml"
else
	# Local development: suppress extension warnings.
	printf '%s' "-n"
fi
