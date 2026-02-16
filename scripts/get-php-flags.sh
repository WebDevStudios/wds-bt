#!/bin/bash
# Get PHP flags based on environment.

if [ -n "$CI" ] || [ -n "$GITHUB_ACTIONS" ] || [ -n "$BUILDKITE" ] || [ -n "$JENKINS_URL" ]; then
	printf '%s' "-d extension=tokenizer -d extension=xmlwriter -d extension=simplexml"
else
	printf '%s' "-n -d display_errors=0 -d log_errors=0"
fi
