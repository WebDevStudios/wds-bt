const { FlatCompat } = require('@eslint/eslintrc');

const compat = new FlatCompat({
	baseDirectory: __dirname,
	recommendedConfig: require('@eslint/js').configs.recommended,
});

// Use the old .eslintrc.json format via compatibility layer
const wordpressConfig = compat.extends(
	'plugin:@wordpress/eslint-plugin/recommended'
);

module.exports = [
	{
		ignores: [
			'blocks/**',
			'build/**',
			'node_modules/**',
			'vendor/**',
			'*.min.js',
		],
	},
	...wordpressConfig.map((config) => {
		// Disable rules that are incompatible with ESLint 9 flat config
		if (config.rules) {
			config.rules['@wordpress/no-unused-vars-before-return'] = 'off';
		}
		return config;
	}),
	...compat.config({
		overrides: [
			{
				files: ['webpack.config.js'],
				env: {
					node: true,
				},
				parserOptions: {
					ecmaVersion: 2021,
					sourceType: 'script',
				},
				rules: {
					'@wordpress/no-unused-vars-before-return': 'off',
				},
			},
		],
	}),
	{
		// Global rule override for ESLint 9 compatibility
		// Disable @wordpress/no-unused-vars-before-return as it uses ESLint 8 API
		rules: {
			'@wordpress/no-unused-vars-before-return': 'off',
		},
	},
];
