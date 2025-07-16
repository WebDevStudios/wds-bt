/* eslint-disable no-console */
const fs = require('fs');

const version = process.env.VERSION;

if (!version) {
	console.error('Error: VERSION environment variable is not set.');
	process.exit(1);
}

const updateJsonFile = (filePath, label = filePath) => {
	if (!fs.existsSync(filePath)) {
		return;
	}

	const jsonData = JSON.parse(fs.readFileSync(filePath, 'utf8'));

	if (jsonData.version === version) {
		console.log(
			`No update needed for ${label} (already version ${version}).`
		);
		return;
	}

	jsonData.version = version;
	fs.writeFileSync(
		filePath,
		JSON.stringify(jsonData, null, 2) + '\n',
		'utf8'
	);
	console.log(`Updated version in ${label} to ${version}`);
};

const updateTextFile = (filePath, regex, replacement, label = filePath) => {
	if (!fs.existsSync(filePath)) {
		return;
	}

	let fileContent = fs.readFileSync(filePath, 'utf8');
	const match = fileContent.match(regex);

	if (match) {
		const currentVersion = match[2];
		if (currentVersion === version) {
			console.log(
				`No update needed for ${label} (already version ${version}).`
			);
			return;
		}

		fileContent = fileContent.replace(regex, replacement);
		fs.writeFileSync(filePath, fileContent, 'utf8');
		console.log(`Updated version in ${label} to ${version}`);
	} else {
		console.warn(`Version pattern not found in ${label}, skipping update.`);
	}
};

// Update all relevant files
updateTextFile(
	'./style.css',
	/(Version:\s*)([^\n]+)/,
	`$1${version}`,
	'style.css'
);
updateTextFile(
	'./README.md',
	/(## Version:\s*)([^\n]+)/,
	`$1${version}`,
	'README.md'
);
updateTextFile(
	'./readme.txt',
	/(Stable tag:\s*)([^\n]+)/,
	`$1${version}`,
	'readme.txt'
);
updateJsonFile('./package.json', 'package.json');

console.log('Version update process completed.');
/* eslint-enable no-console */
