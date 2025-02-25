/**
 * Dependencies
 */
const { join } = require('path');

module.exports = {
	defaultValues: {
		transformer: (view) => {
			const {
				variantVars: { isInteractiveVariant },
			} = view;
			return {
				...view,
				requiresAtLeast: isInteractiveVariant ? '6.5' : '6.1',
			};
		},
		author: 'WebDevStudios',
		category: 'wds-blocks',
		dashicon: 'pets',
		description: 'A custom block created by the create-block for the theme',
		namespace: 'wdsbt',
		editorScript: 'file:./index.js',
		editorStyle: 'file:./index.css',
		style: 'file:./style.css',
		render: 'file:./render.php',
		viewScriptModule: 'file:./view.js',
		version: '1.0.0',
		customPackageJSON: {
			prettier: '@wordpress/prettier-config',
		},
	},
	variants: {
		static: {},
		dynamic: {},
		interactive: {
			customScripts: {
				build: 'wp-scripts build --experimental-modules',
				start: 'wp-scripts start --experimental-modules',
			},
			supports: {
				interactive: true,
			},
		},
	},
	pluginTemplatesPath: join(__dirname, 'plugin'),
	blockTemplatesPath: join(__dirname, 'block'),
};
