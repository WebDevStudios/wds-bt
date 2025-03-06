/* eslint-disable no-console */
const fs = require('fs');

const version = process.env.VERSION;

if (!version) {
	console.error('Error: VERSION environment variable is not set.');
	process.exit(1);
}

const updateJsonFile = (filePath) => {
	if (!fs.existsSync(filePath)) {
		return;
	}

	const jsonData = JSON.parse(fs.readFileSync(filePath, 'utf8'));

	if (jsonData.version === version) {
		console.log(
			`No update needed for ${filePath} (already version ${version}).`
		);
		return;
	}

	jsonData.version = version;
	fs.writeFileSync(
		filePath,
		JSON.stringify(jsonData, null, 2) + '\n',
		'utf8'
	);
	console.log(`Updated version in ${filePath} to ${version}`);
};

const updateTextFile = (filePath, regex, replacement) => {
	if (!fs.existsSync(filePath)) {
		return;
	}

	let fileContent = fs.readFileSync(filePath, 'utf8');

	if (regex.test(fileContent)) {
		fileContent = fileContent.replace(regex, replacement);
		fs.writeFileSync(filePath, fileContent, 'utf8');
		console.log(`Updated version in ${filePath} to ${version}`);
	} else {
		console.warn(
			`Version pattern not found in ${filePath}, skipping update.`
		);
	}
};

// Update version in style.css
updateTextFile('./style.css', /(Version:\s*)([^\n]+)/, `$1${version}`);

// Update version in README.md
updateTextFile('./README.md', /(## Version:\s*)([^\n]+)/, `$1${version}`);

// Update JSON-based files
updateJsonFile('./package.json');
updateJsonFile('./composer.json');

console.log('Version update process completed.');
/* eslint-enable no-console */
