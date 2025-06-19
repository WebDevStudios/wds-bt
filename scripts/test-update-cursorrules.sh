#!/bin/bash

# Test script to verify PHP version extraction from different composer.json formats

set -e

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

print_test() {
    echo -e "${GREEN}[TEST]${NC} $1"
}

print_result() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}[PASS]${NC} $2"
    else
        echo -e "${RED}[FAIL]${NC} $2"
    fi
}

# Test 1: Platform config format (current format)
print_test "Testing platform config format..."
echo '{"config": {"platform": {"php": "8.2"}}}' > test-composer.json
PHP_VERSION=$(jq -r '.config.platform.php // empty' test-composer.json 2>/dev/null)
PHP_VERSION=$(echo "$PHP_VERSION" | sed 's/[^0-9.]//g')
if [ "$PHP_VERSION" = "8.2" ]; then
    print_result 0 "Platform config format works"
else
    print_result 1 "Platform config format failed: got $PHP_VERSION"
fi

# Test 2: Require-dev format
print_test "Testing require-dev format..."
echo '{"require-dev": {"php": ">=8.2"}}' > test-composer.json
PHP_VERSION=$(jq -r '.require-dev.php // empty' test-composer.json 2>/dev/null)
PHP_VERSION=$(echo "$PHP_VERSION" | sed 's/[^0-9.]//g')
if [ "$PHP_VERSION" = "8.2" ]; then
    print_result 0 "Require-dev format works"
else
    print_result 1 "Require-dev format failed: got $PHP_VERSION"
fi

# Test 3: Require format
print_test "Testing require format..."
echo '{"require": {"php": "^8.2.0"}}' > test-composer.json
PHP_VERSION=$(jq -r '.require.php // empty' test-composer.json 2>/dev/null)
PHP_VERSION=$(echo "$PHP_VERSION" | sed 's/[^0-9.]//g')
if [ "$PHP_VERSION" = "8.2.0" ]; then
    print_result 0 "Require format works"
else
    print_result 1 "Require format failed: got $PHP_VERSION"
fi

# Test 4: Simple version constraint
print_test "Testing simple version constraint..."
echo '{"require": {"php": "~8.2.0"}}' > test-composer.json
PHP_VERSION=$(jq -r '.require.php // empty' test-composer.json 2>/dev/null)
PHP_VERSION=$(echo "$PHP_VERSION" | sed 's/[^0-9.]//g')
if [ "$PHP_VERSION" = "8.2.0" ]; then
    print_result 0 "Simple version constraint works"
else
    print_result 1 "Simple version constraint failed: got $PHP_VERSION"
fi

# Cleanup
rm -f test-composer.json

print_test "All tests completed!"
