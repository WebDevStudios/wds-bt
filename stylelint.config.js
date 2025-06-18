const wordpressConfig = require('@wordpress/stylelint-config');

const baseConfig = Array.isArray(wordpressConfig)
	? wordpressConfig[0]
	: wordpressConfig;

module.exports = {
	...baseConfig,
	customSyntax: 'postcss-scss',
	rules: {
		...baseConfig.rules,
		'declaration-no-important': true,
		'no-descending-specificity': null,
		'selector-class-pattern': null,
		'at-rule-no-unknown': [
			true,
			{
				ignoreAtRules: [
					'apply',
					'layer',
					'variants',
					'responsive',
					'screen',
					'use',
					'include',
					'each',
					'if',
					'else',
					'for',
					'while',
					'function',
					'return',
					'mixin',
					'content',
					'extend',
					'warn',
					'error',
					'debug',
				],
			},
		],
		'declaration-property-unit-allowed-list': [
			{
				'font-size': ['em', 'rem'],
				'line-height': [],
				'/^border/': ['px'],
				'/^margin/': ['em', 'rem'],
				'/^padding/': ['em', 'rem'],
			},
		],
		'no-invalid-double-slash-comments': null,
		'comment-no-empty': null,
	},
};
