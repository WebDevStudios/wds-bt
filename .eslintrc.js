module.exports = {
	root: true,
	extends: [require.resolve('@wordpress/eslint-plugin/configs/recommended')],
	rules: {
		camelcase: 'warn',
		'@wordpress/no-global-event-listener': 'off',
	},
	env: {
		browser: true,
		node: true,
	},
	globals: {
		jQuery: 'readonly',
	},
};
