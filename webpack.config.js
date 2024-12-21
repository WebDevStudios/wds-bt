// WordPress webpack config.
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

// Plugins.
// eslint-disable-next-line import/no-extraneous-dependencies
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

// Utilities.
const path = require('path');
const glob = require('glob');

// Dynamically generate entry points for each file inside 'assets/scss/blocks'
const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/core/*.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath.split(/[\\/]/).pop().replace('.scss', '');
		acc[`css/blocks/${entryKey}`] = filePath;
		return acc;
	}, {});

// Add any new entry points by extending the webpack config.
module.exports = {
	...defaultConfig,
	...{
		entry: {
			'js/editor': path.resolve(process.cwd(), 'assets/js', 'index.js'),
			'css/style': path.resolve(
				process.cwd(),
				'assets/scss',
				'_index.scss'
			),
			'css/editor': path.resolve(
				process.cwd(),
				'assets/scss',
				'editor.scss'
			),
			...coreBlockEntryPaths,
		},
		plugins: [
			// Include WP's plugin config.
			...defaultConfig.plugins,

			// Removes the empty `.js` files generated by webpack but
			// sets it after WP has generated its `*.asset.php` file.
			new RemoveEmptyScriptsPlugin({
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			}),
		],
	},
};
