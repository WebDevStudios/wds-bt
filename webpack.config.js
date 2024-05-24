/* eslint-disable eslint-comments/disable-enable-pair */
/* eslint-disable import/no-extraneous-dependencies */
const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const CopyPlugin = require('@wordpress/scripts/node_modules/copy-webpack-plugin');
const {
	CleanWebpackPlugin,
} = require('@wordpress/scripts/node_modules/clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const { glob } = require('glob');

// Dynamically generate entry points for each file inside 'assets/scss/blocks'
const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/**/*.scss', {
		posix: true,
		dotRelative: true,
	})
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace('./assets/scss/', '')
			.replace('.scss', '');
		acc[entryKey] = filePath;
		return acc;
	}, {});

module.exports = {
	...defaultConfig,
	entry: {
		style: './assets/scss/index.scss',
		index: './assets/js/index.js',
		variations: './assets/js/block-variations/index.js',
		filters: './assets/js/block-filters/index.js',
		...coreBlockEntryPaths,
	},
	output: {
		filename: 'js/[name].js',
		path: path.resolve(__dirname, 'build'),
	},
	module: {
		rules: [
			{
				test: /\.(webp|png|jpe?g|gif)$/,
				type: 'asset/resource',
				generator: {
					filename: 'images/[name][ext]',
				},
			},
			{
				test: /\.(sa|sc|c)ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					'css-loader',
					'postcss-loader',
					'sass-loader',
				],
			},
			{
				test: /\.svg$/,
				type: 'asset/inline',
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
		],
	},
	plugins: [
		...defaultConfig.plugins,

		new MiniCssExtractPlugin({
			filename: 'css/[name].css',
		}),

		new CopyPlugin({
			patterns: [
				{
					from: '**/*.{jpg,jpeg,png,gif,svg}',
					to: 'images/[path][name][ext]',
					context: path.resolve(process.cwd(), 'assets/images'),
					noErrorOnMissing: true,
				},
				{
					from: '*.svg',
					to: 'images/icons/[name][ext]',
					context: path.resolve(process.cwd(), 'assets/images/icons'),
					noErrorOnMissing: true,
				},
				{
					from: '**/*.{woff,woff2,eot,ttf,otf}',
					to: 'fonts/[path][name][ext]',
					context: path.resolve(process.cwd(), 'assets/fonts'),
					noErrorOnMissing: true,
				},
			],
		}),

		new CleanWebpackPlugin(),

		new ESLintPlugin(),
		new StylelintPlugin(),
	],
};
/* eslint-enable eslint-comments/disable-enable-pair */
/* eslint-enable import/no-extraneous-dependencies */
