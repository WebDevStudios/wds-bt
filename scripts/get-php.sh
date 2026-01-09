#!/bin/bash
# Auto-detect PHP binary path for cross-platform compatibility
# Works on Mac (Homebrew), Linux, and CI/CD environments

# Check if PHP_BIN environment variable is set
if [ -n "$PHP_BIN" ]; then
	echo "$PHP_BIN"
	exit 0
fi

# Try common paths in order of preference
PATHS=(
	"/opt/homebrew/bin/php"  # Homebrew on Apple Silicon Mac
	"/usr/local/bin/php"      # Homebrew on Intel Mac / Linux
	"/usr/bin/php"            # Standard Linux path
	"php"                     # Fallback to PATH
)

for path in "${PATHS[@]}"; do
	if command -v "$path" >/dev/null 2>&1; then
		# Verify it's actually PHP
		if "$path" -v >/dev/null 2>&1; then
			echo "$path"
			exit 0
		fi
	fi
done

# Final fallback - use 'php' from PATH
echo "php"
exit 0
