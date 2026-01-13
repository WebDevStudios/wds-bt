/* eslint-disable no-console */
const { spawn } = require('child_process');
const https = require('https');
const { parseStringPromise } = require('xml2js');
const fs = require('fs');
const path = require('path');

function runCommand(command) {
	return new Promise((resolve, reject) => {
		const child = spawn(command, { shell: true });
		let stdout = '';
		let stderr = '';
		child.stdout.on('data', (data) => {
			const text = data.toString();
			stdout += text;
			process.stdout.write(text);
		});
		child.stderr.on('data', (data) => {
			const text = data.toString();
			stderr += text;
			process.stderr.write(text);
		});
		child.on('close', (code) => {
			resolve({ stdout, stderr, code });
		});
		child.on('error', (err) => {
			reject(err);
		});
	});
}

// Create an HTTPS agent that ignores SSL certificate errors
const httpsAgent = new https.Agent({
	rejectUnauthorized: false,
});

async function run() {
	// Dynamically import inquirer and node-fetch
	const [{ default: inquirer }, { default: fetch }] = await Promise.all([
		import('inquirer'),
		import('node-fetch'),
	]);

	// Prompt for the URL or attempt to fetch site_url dynamically
	const answers = await inquirer.prompt([
		{
			type: 'input',
			name: 'url',
			message:
				'Enter the site URL (leave blank to auto-detect via WordPress REST API):',
			validate(value) {
				if (!value) {
					return true;
				}
				const pass = value.match(/^https?:\/\/[^\s$.?#].[^\s]*$/);
				return pass ? true : 'Please enter a valid URL';
			},
		},
	]);

	let url = answers.url;

	// Auto-detect the site URL via WordPress REST API if not provided
	if (!url) {
		try {
			const wpResponse = await fetch('https://wdsbt.local/wp-json', {
				agent: httpsAgent,
			});
			if (wpResponse.ok) {
				const data = await wpResponse.json();
				// Use the "home" property which contains the absolute URL
				url = data?.home || 'https://wdsbt.local';
				console.log(`Detected site URL: ${url}`);
			} else {
				console.warn(
					'Failed to fetch site URL. Using default: https://wdsbt.local'
				);
				url = 'https://wdsbt.local';
			}
		} catch (error) {
			console.warn(`Error fetching site URL: ${error.message}`);
			url = 'https://wdsbt.local';
		}
	}

	// Normalize URL by removing trailing slash if it exists
	if (url.endsWith('/')) {
		url = url.slice(0, -1);
	}

	// Prepare report folder with naming convention: pa11y-ci-report/YYYY-MM-DD-site
	const date = new Date().toISOString().split('T')[0];
	const siteName = url.replace(/^https?:\/\//, '').replace(/[\/:]/g, '-');
	const reportFolder = path.join('pa11y-ci-report', `${date}-${siteName}`);
	if (!fs.existsSync(reportFolder)) {
		fs.mkdirSync(reportFolder, { recursive: true });
		console.log(`Report folder created at ${reportFolder}`);
	}

	const sitemapUrl = `${url}/wp-sitemap.xml`;

	try {
		const response = await fetch(sitemapUrl, { agent: httpsAgent });
		if (response.ok) {
			console.log(
				`Sitemap found at ${sitemapUrl}. Parsing for sub-sitemaps...`
			);
			const sitemapXml = await response.text();
			const parsedXml = await parseStringPromise(sitemapXml);
			const sitemaps =
				parsedXml?.urlset?.url?.map((entry) => entry.loc[0]) || [];

			if (sitemaps.length > 0) {
				console.log(
					`Found ${sitemaps.length} sub-sitemaps. Running pa11y-ci on each...`
				);
				let counter = 1;
				for (const sitemap of sitemaps) {
					console.log(`Testing ${sitemap}`);
					const reportFile = path.join(
						reportFolder,
						`report-${counter}.txt`
					);
					const command = `pa11y-ci --sitemap ${sitemap}`;
					const result = await runCommand(command);
					if (result.code !== 0) {
						console.error(
							`pa11y-ci exited with code ${result.code}`
						);
					}
					fs.writeFileSync(reportFile, result.stdout + result.stderr);
					console.log(`Report saved to ${reportFile}`);
					counter++;
				}
			} else {
				console.log(
					'No sub-sitemaps found. Running pa11y-ci on the main page...'
				);
				const reportFile = path.join(reportFolder, 'main-report.txt');
				const command = `pa11y-ci ${url}`;
				const result = await runCommand(command);
				if (result.code !== 0) {
					console.error(`pa11y-ci exited with code ${result.code}`);
				}
				fs.writeFileSync(reportFile, result.stdout + result.stderr);
				console.log(`Report saved to ${reportFile}`);
			}
		} else {
			console.log(
				`No sitemap found at ${sitemapUrl}. Running pa11y-ci on the main page...`
			);
			const reportFile = path.join(reportFolder, 'main-report.txt');
			const command = `pa11y-ci ${url}`;
			const result = await runCommand(command);
			if (result.code !== 0) {
				console.error(`pa11y-ci exited with code ${result.code}`);
			}
			fs.writeFileSync(reportFile, result.stdout + result.stderr);
			console.log(`Report saved to ${reportFile}`);
		}
	} catch (err) {
		console.error(`Execution error: ${err.message}`);
	}
}

run();
/* eslint-enable no-console */
