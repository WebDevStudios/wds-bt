/* eslint-disable no-console */
const { exec } = require('child_process');
const { promisify } = require('util');

const execAsync = promisify(exec);
const defaultPath = 'https://wdsbt.local'; // Update this to your theme folder path

async function run() {
	// Dynamically import inquirer
	const { default: inquirer } = await import('inquirer');

	// Prompt for the URL
	const answers = await inquirer.prompt([
		{
			type: 'input',
			name: 'url',
			message:
				'Please enter the URL to test for accessibility (leave blank to use your local):',
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
	try {
		const { stdout, stderr } = await execAsync(`pa11y-ci ${url}`);
		console.log(stdout);
		if (stderr) {
			console.error(`Error: ${stderr}`);
		}
	} catch (err) {
		console.error(`Execution error: ${err}`);
	}
}

run();
/* eslint-enable no-console */
