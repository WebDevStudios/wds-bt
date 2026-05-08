#!/usr/bin/env node
/**
 * Theme tooling: postinstall (Windows-friendly), optional cursorrules, format:js.
 * Usage: node scripts/theme-tools.cjs postinstall | cursorrules | format-js
 */

'use strict';

const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');

function ensureAjvKeywordsShim() {
	const schemaAjv = path.join(
		root,
		'node_modules',
		'schema-utils',
		'node_modules',
		'ajv'
	);
	const linkDir = path.join(
		root,
		'node_modules',
		'ajv-keywords',
		'node_modules'
	);
	const linkPath = path.join(linkDir, 'ajv');
	try {
		if (!fs.existsSync(schemaAjv)) {
			return;
		}
		fs.mkdirSync(linkDir, { recursive: true });
		if (fs.existsSync(linkPath)) {
			return;
		}
		const rel = path.relative(linkDir, schemaAjv);
		fs.symlinkSync(rel, linkPath, 'dir');
	} catch {
		// Symlinks may be disallowed on Windows without Developer Mode; overrides still apply.
	}
}

function runNpx(args) {
	const result = spawnSync('npx', args, {
		stdio: 'inherit',
		cwd: root,
		shell: true,
		env: process.env,
	});
	const code = result.status ?? (result.signal ? 1 : 0);
	if (code !== 0) {
		process.exit(code);
	}
}

function bashCandidates() {
	if (process.platform !== 'win32') {
		return ['bash'];
	}
	const out = ['bash'];
	const pf = process.env.ProgramFiles;
	const pfx86 = process.env['ProgramFiles(x86)'];
	if (pf) {
		out.push(path.join(pf, 'Git', 'bin', 'bash.exe'));
	}
	if (pfx86) {
		out.push(path.join(pfx86, 'Git', 'bin', 'bash.exe'));
	}
	return out;
}

function runUpdateCursorrules() {
	if (process.env.CI || process.env.BUDDY) {
		return 0;
	}
	const sh = path.join(root, 'scripts', 'update-cursorrules.sh');
	for (const bash of bashCandidates()) {
		const result = spawnSync(bash, [sh], {
			stdio: 'inherit',
			cwd: root,
			env: process.env,
		});
		if (result.error) {
			if (result.error.code === 'ENOENT') {
				continue;
			}
			return 1;
		}
		const code = result.status ?? (result.signal ? 1 : 0);
		return code;
	}
	// eslint-disable-next-line no-console
	console.warn(
		'[wds-bt] Skipping scripts/update-cursorrules.sh (bash not found). Install Git for Windows or run it manually if you use Cursor rules sync.'
	);
	return 0;
}

function runFormat(
	glob,
	{ allowNoMatchingFiles } = { allowNoMatchingFiles: false }
) {
	const result = spawnSync('npx', ['wp-scripts', 'format', glob], {
		cwd: root,
		shell: true,
		env: process.env,
		encoding: 'utf8',
		stdio: ['inherit', 'pipe', 'pipe'],
	});
	const code = result.status ?? (result.signal ? 1 : 0);
	if (code === 0) {
		if (result.stdout) {
			process.stdout.write(result.stdout);
		}
		if (result.stderr) {
			process.stderr.write(result.stderr);
		}
		return 0;
	}
	const combined = `${result.stdout || ''}${result.stderr || ''}`;
	const noFilesOk =
		allowNoMatchingFiles &&
		/no files matching the pattern|No files matching/i.test(combined);
	if (noFilesOk) {
		const lines = combined.split(/\r?\n/).filter((line) => {
			const t = line.trim();
			if (t.length === 0) {
				return false;
			}
			if (/no files matching/i.test(t)) {
				return false;
			}
			if (/^npm warn\b/i.test(t)) {
				return false;
			}
			return true;
		});
		if (lines.length === 0) {
			return 0;
		}
	}
	if (result.stdout) {
		process.stdout.write(result.stdout);
	}
	if (result.stderr) {
		process.stderr.write(result.stderr);
	}
	return code;
}

function runFormatJs() {
	const jsCode = runFormat('./assets/**/*.js', {
		allowNoMatchingFiles: false,
	});
	if (jsCode !== 0) {
		process.exit(jsCode);
	}
	process.exit(
		runFormat('./assets/**/*.jsx', { allowNoMatchingFiles: true })
	);
}

function runPostinstall() {
	ensureAjvKeywordsShim();
	runNpx(['lefthook', 'install', '--reset-hooks-path']);
	process.exit(runUpdateCursorrules());
}

const cmd = process.argv[2];
if (cmd === 'postinstall') {
	runPostinstall();
} else if (cmd === 'cursorrules') {
	process.exit(runUpdateCursorrules());
} else if (cmd === 'format-js') {
	runFormatJs();
} else {
	// eslint-disable-next-line no-console
	console.error(
		'Usage: node scripts/theme-tools.cjs postinstall|cursorrules|format-js'
	);
	process.exit(1);
}
