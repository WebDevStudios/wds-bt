#!/usr/bin/env node
/**
 * Cross-platform postinstall: ajv-keywords shim, lefthook, update-cursorrules.
 * Replaces a shell-only postinstall so `npm install` works on Windows (cmd.exe).
 */

'use strict';

const { spawnSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const root = path.resolve(__dirname, '..');

function ensureAjvKeywordsShim() {
	const schemaAjv = path.join(root, 'node_modules', 'schema-utils', 'node_modules', 'ajv');
	const linkDir = path.join(root, 'node_modules', 'ajv-keywords', 'node_modules');
	const linkPath = path.join(linkDir, 'ajv');
	try {
		if (!fs.existsSync(schemaAjv)) return;
		fs.mkdirSync(linkDir, { recursive: true });
		if (fs.existsSync(linkPath)) return;
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
	if (code !== 0) process.exit(code);
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
			process.exit(1);
		}
		const code = result.status ?? (result.signal ? 1 : 0);
		process.exit(code);
	}
	console.warn(
		'[wds-bt] Skipping scripts/update-cursorrules.sh (bash not found). Install Git for Windows or run it manually if you use Cursor rules sync.',
	);
	process.exit(0);
}

ensureAjvKeywordsShim();
runNpx(['lefthook', 'install', '--reset-hooks-path']);
runUpdateCursorrules();
