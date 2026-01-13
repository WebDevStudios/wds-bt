#!/usr/bin/env node
/**
 * Auto-detect PHP binary path for cross-platform compatibility.
 * Works on Mac (Homebrew), Linux, and CI/CD environments.
 */

const { execSync } = require('child_process');
const fs = require('fs');

// Check if PHP_BIN environment variable is set.
if (process.env.PHP_BIN) {
	process.stdout.write(process.env.PHP_BIN);
	process.exit(0);
}

// Try common paths in order of preference.
const paths = [
	'/opt/homebrew/bin/php', // Homebrew on Apple Silicon Mac.
	'/usr/local/bin/php', // Homebrew on Intel Mac / Linux.
	'/usr/bin/php', // Standard Linux path.
];

for (const phpPath of paths) {
	if (fs.existsSync(phpPath)) {
		try {
			// Verify it's actually PHP.
			execSync(`${phpPath} -v`, { stdio: 'ignore' });
			process.stdout.write(phpPath);
			process.exit(0);
		} catch {
			// Continue to next path.
		}
	}
}

// Final fallback - use 'php' from PATH.
process.stdout.write('php');
process.exit(0);
