#!/usr/bin/env node
/* eslint-disable no-console */
/**
 * Run Lighthouse (mobile + desktop) and show category scores in the terminal (no files written).
 *
 * Usage:
 *   npm run lighthouse                    # prompt for URL or auto-detect local
 *   npm run lighthouse -- https://mysite.local
 */

const { spawn } = require('child_process');
const https = require('https');
const path = require('path');

try {
	require('dotenv').config({ path: path.resolve(__dirname, '../.env') });
} catch {
	// dotenv optional
}

const DEFAULT_URL = 'https://wdsbt.local';

function getUrlFromArgv() {
	const args = process.argv.slice(2).filter((a) => !a.startsWith('--'));
	const urlArg = args.find((a) => /^https?:\/\//i.test(a));
	return urlArg ? urlArg.replace(/\/$/, '') : null;
}

async function autoDetectUrl() {
	const httpsAgent = new https.Agent({ rejectUnauthorized: false });
	try {
		const { default: fetch } = await import('node-fetch');
		const res = await fetch(`${DEFAULT_URL}/wp-json`, {
			agent: httpsAgent,
		});
		if (res.ok) {
			const data = await res.json();
			const home = data?.home || DEFAULT_URL;
			console.log(`Detected site URL: ${home}`);
			return home.replace(/\/$/, '');
		}
	} catch (err) {
		console.warn(
			`Auto-detect failed: ${err.message}. Using ${DEFAULT_URL}`
		);
	}
	return DEFAULT_URL;
}

async function getUrl() {
	const fromArg = getUrlFromArgv();
	if (fromArg) {
		return fromArg;
	}

	const { default: inquirer } = await import('inquirer');
	const { url: input } = await inquirer.prompt([
		{
			type: 'input',
			name: 'url',
			message:
				'Enter the site URL (leave blank to auto-detect via WordPress REST API):',
			validate(value) {
				if (!value) {
					return true;
				}
				return /^https?:\/\/[^\s$.?#].[^\s]*$/.test(value)
					? true
					: 'Please enter a valid URL';
			},
		},
	]);

	if (input && input.trim()) {
		return input.trim().replace(/\/$/, '');
	}
	return autoDetectUrl();
}

const LABELS = {
	performance: 'Performance',
	accessibility: 'Accessibility',
	'best-practices': 'Best Practices',
	seo: 'SEO',
};

function printScores(data, heading) {
	const categories = data?.categories || {};
	console.log(`\n${heading}\n`);
	for (const [id, label] of Object.entries(LABELS)) {
		const score = categories[id]?.score;
		const num =
			score !== undefined && score !== null
				? Math.round(Number(score) * 100)
				: '—';
		console.log(`  ${label}: ${num}`);
	}
}

function runLighthouse(url, preset) {
	return new Promise((resolve, reject) => {
		const args = [
			url,
			'--output=json',
			'--output-path=stdout',
			'--chrome-flags=--headless --no-sandbox --disable-dev-shm-usage',
			'--quiet',
		];
		if (preset === 'desktop') {
			args.push('--preset=desktop');
		}
		// mobile: no preset (Lighthouse defaults to mobile emulation)
		let stdout = '';
		const child = spawn('npx', ['lighthouse', ...args], {
			stdio: ['inherit', 'pipe', 'inherit'],
			cwd: path.resolve(__dirname, '..'),
		});
		child.stdout.on('data', (chunk) => {
			stdout += chunk;
		});
		child.on('close', (code) => {
			if (code !== 0) {
				reject(new Error(`Lighthouse exited with code ${code}`));
				return;
			}
			try {
				resolve(JSON.parse(stdout));
			} catch (err) {
				reject(err);
			}
		});
		child.on('error', reject);
	});
}

async function run() {
	const url = await getUrl();

	console.log('\nRunning Lighthouse (mobile)...');
	const mobileData = await runLighthouse(url, 'mobile');

	console.log('Running Lighthouse (desktop)...');
	const desktopData = await runLighthouse(url, 'desktop');

	console.log('\n---');
	printScores(mobileData, 'Mobile');
	printScores(desktopData, 'Desktop');
	console.log('');

	process.exit(0);
}

run().catch((err) => {
	console.error(err);
	process.exit(1);
});
