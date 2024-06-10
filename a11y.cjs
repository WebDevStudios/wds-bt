/* eslint-disable no-console */
const { exec } = require('child_process');
const { promisify } = require('util');
const https = require('https');

const execAsync = promisify(exec);
const defaultPath = 'https://wdsbt.local'; // Update this to your theme folder path

// Create an HTTPS agent that ignores SSL certificate errors
const httpsAgent = new https.Agent({
	rejectUnauthorized: false,
});

async function run() {
	// Dynamically import inquirer and node-fetch
	const [{ default: inquirer }, { default: fetch }] = await Promise.all([
		import('inquirer'),
		// eslint-disable-next-line import/no-extraneous-dependencies
		import('node-fetch'),
	]);

	// Prompt for the URL
	const answers = await inquirer.prompt([
		{
			type: 'input',
			name: 'url',
			message:
				'Please enter the URL to test for accessibility (leave blank to use your local: https://wdsbt.local):',
			validate(value) {
				if (!value) {
					return true; // Allow empty input
				}
				const pass = value.match(/^https?:\/\/[^\s$.?#].[^\s]*$/);
				if (pass) {
					return true;
				}
				return 'Please enter a valid URL';
			},
		},
	]);

	const url = answers.url || defaultPath; // Use default path if no URL is provided
	const sitemapUrl = `${url}/wp-sitemap.xml`;

	try {
		const response = await fetch(sitemapUrl, { agent: httpsAgent });
		if (response.ok) {
			console.log(
				`Sitemap found at ${sitemapUrl}. Running pa11y-ci on the sitemap...`
			);
			const { stdout, stderr } = await execAsync(
				`pa11y-ci --sitemap ${sitemapUrl}`
			);
			console.log(stdout);
			if (stderr) {
				console.error(`Error: ${stderr}`);
			}
		} else {
			console.log(
				`No sitemap found at ${sitemapUrl}. Running pa11y-ci on the main page...`
			);
			const { stdout, stderr } = await execAsync(`pa11y-ci ${url}`);
			console.log(stdout);
			if (stderr) {
				console.error(`Error: ${stderr}`);
			}
		}
	} catch (err) {
		console.error(`Execution error: ${err}`);
	}
}

run();
/* eslint-enable no-console */
