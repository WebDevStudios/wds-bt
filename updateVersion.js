const fs = require('fs');
const path = require('path');
const dotenv = require('dotenv');

// Load environment variables from .env file without removing the comments
dotenv.config();

const version = process.env.VERSION; // Manually set version
let build = parseInt(process.env.BUILD, 10);

// Increment the build number
build += 1;

// Format the build as `0x` (e.g., 01, 02, 03)
const formattedBuild = `${build}`;

// Read the original .env file as text
let envContent = fs.readFileSync('.env', 'utf8');

// Use regex to find and update the BUILD value while preserving comments
envContent = envContent.replace(/(BUILD\s*=\s*)\d+/i, `$1${build}`);

// Write the updated .env content back to the file
fs.writeFileSync('.env', envContent, 'utf8');

// Function to update version + build in style.css
const updateStyleCssVersion = (cssFilePath) => {
	let cssContent = fs.readFileSync(cssFilePath, 'utf8');

	// Replace the Version line with VERSION + formattedBuild
	const newVersion = `${version}${formattedBuild}`;
	cssContent = cssContent.replace(
		/Version:\s*\d+\.\d+\.\d+/i,
		`Version: ${newVersion}`
	);

	fs.writeFileSync(cssFilePath, cssContent, 'utf8');
};

// Function to update version in JSON files (composer.json and package.json)
const updateJsonVersion = (filePath) => {
	const fileContent = fs.readFileSync(filePath, 'utf8');
	const jsonContent = JSON.parse(fileContent);
	jsonContent.version = version; // Only update with VERSION, no BUILD
	fs.writeFileSync(filePath, JSON.stringify(jsonContent, null, 2), 'utf8');
};

// Paths to the files you want to update
const styleCssPath = path.resolve(__dirname, './style.css'); // Path to your root style.css
const packageJsonPath = path.resolve(__dirname, 'package.json');
const composerJsonPath = path.resolve(__dirname, 'composer.json');

// Update version + build in style.css
updateStyleCssVersion(styleCssPath);

// Update version in composer.json and package.json (VERSION only)
updateJsonVersion(packageJsonPath);
updateJsonVersion(composerJsonPath);
