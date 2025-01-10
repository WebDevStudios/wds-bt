const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin');
// eslint-disable-next-line import/no-extraneous-dependencies
const fs = require('fs-extra');
const path = require('path');
const glob = require('glob');

// Detect Windows platform to handle backslashes
const isWin = process.platform === 'win32';

const blockStyleEntries = glob
	.sync('./assets/blocks/**/style.scss', { dotRelative: true })
	.reduce((entries, filePath) => {
		const blockName = isWin
			? filePath
					.replace('./assets\\blocks\\', '')
					.replace('\\style.scss', '')
			: filePath
					.replace('./assets/blocks/', '')
					.replace('/style.scss', '');

		entries[`../blocks/${blockName}/style`] = filePath;
		return entries;
	}, {});

const blockEditorEntries = glob
	.sync('./assets/blocks/**/editor.scss', { dotRelative: true })
	.reduce((entries, filePath) => {
		const blockName = isWin
			? filePath
					.replace('./assets\\blocks\\', '')
					.replace('\\editor.scss', '')
			: filePath
					.replace('./assets/blocks/', '')
					.replace('/editor.scss', '');

		entries[`../blocks/${blockName}/editor`] = filePath;
		return entries;
	}, {});

const blockIndexEntries = glob
	.sync('./assets/blocks/**/index.js', { dotRelative: true })
	.reduce((entries, filePath) => {
		const blockName = isWin
			? filePath
					.replace('./assets\\blocks\\', '')
					.replace('\\index.js', '')
			: filePath.replace('./assets/blocks/', '').replace('/index.js', '');

		entries[`../blocks/${blockName}/index`] = filePath;
		return entries;
	}, {});

const blockViewEntries = glob
	.sync('./assets/blocks/**/view.js', { dotRelative: true })
	.reduce((entries, filePath) => {
		const blockName = isWin
			? filePath
					.replace('./assets\\blocks\\', '')
					.replace('\\view.js', '')
			: filePath.replace('./assets/blocks/', '').replace('/view.js', '');

		entries[`../blocks/${blockName}/view`] = filePath;
		return entries;
	}, {});

const coreBlockEntries = glob
	.sync('./assets/scss/blocks/core/*.scss', { dotRelative: true })
	.reduce((entries, filePath) => {
		const fileName = path.basename(filePath, '.scss');
		entries[`css/blocks/${fileName}`] = filePath;
		return entries;
	}, {});

const otherEntries = {
	'js/index': path.resolve(__dirname, './assets/js/index.js'),
	'js/variations': path.resolve(
		__dirname,
		'./assets/js/block-variations/index.js'
	),
	'js/filters': path.resolve(__dirname, './assets/js/block-filters/index.js'),
	'css/style': path.resolve(__dirname, './assets/scss/_index.scss'),
	'css/editor': path.resolve(__dirname, './assets/scss/editor.scss'),
};

const entryPoints = {
	...otherEntries,
	...coreBlockEntries,
	...blockStyleEntries,
	...blockEditorEntries,
	...blockIndexEntries,
	...blockViewEntries,
};

module.exports = {
	...defaultConfig,
	entry: entryPoints,

	plugins: [
		...defaultConfig.plugins,

		new RemoveEmptyScriptsPlugin({
			stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
		}),

		new CopyWebpackPlugin({
			patterns: [
				{
					from: './assets/blocks/**/block.json',
					to: ({ absoluteFilename }) => {
						let rel = path.relative(
							'./assets/blocks',
							absoluteFilename
						);
						rel = rel.replace(/(\\|\/)block\.json$/, '');
						return `../blocks/${rel}/block.json`;
					},
				},
				{
					from: './assets/blocks/**/render.php',
					to: ({ absoluteFilename }) => {
						let rel = path.relative(
							'./assets/blocks',
							absoluteFilename
						);
						rel = rel.replace(/(\\|\/)render\.php$/, '');
						return `../blocks/${rel}/render.php`;
					},
				},
			],
		}),
		{
			apply: (compiler) => {
				compiler.hooks.afterEmit.tap('RemoveBuildBlocksPlugin', () => {
					const buildBlocksPath = path.resolve(
						__dirname,
						'build/blocks'
					);
					if (fs.existsSync(buildBlocksPath)) {
						fs.removeSync(buildBlocksPath);
					}
				});
			},
		},
		{
			apply: (compiler) => {
				compiler.hooks.afterEmit.tap('RemoveAssetPhpPlugin', () => {
					const pathsToRemove = [
						...glob.sync(
							path.resolve(__dirname, 'blocks/**/*.asset.php')
						),
						...glob.sync(
							path.resolve(__dirname, 'build/**/*.asset.php')
						),
					];

					pathsToRemove.forEach((filePath) => {
						if (fs.existsSync(filePath)) {
							fs.removeSync(filePath);
						}
					});
				});
			},
		},
	],
};
