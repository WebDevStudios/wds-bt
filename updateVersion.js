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

const stylePath = './style.css';
if (fs.existsSync(stylePath)) {
	let styleContent = fs.readFileSync(stylePath, 'utf8');
	const currentVersionMatch = styleContent.match(/(Version:\s*)([^\n]+)/);

	if (currentVersionMatch && currentVersionMatch[2].trim() === version) {
		console.log(
			`No update needed for ${stylePath} (already version ${version}).`
		);
	} else {
		styleContent = styleContent.replace(
			/(Version:\s*)([^\n]+)/,
			`$1${version}`
		);
		fs.writeFileSync(stylePath, styleContent, 'utf8');
		console.log(`Updated version in ${stylePath} to ${version}`);
	}
}

updateJsonFile('./package.json');

updateJsonFile('./composer.json');

console.log('Version update process completed.');
/* eslint-enable no-console */
