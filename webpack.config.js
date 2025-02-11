const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemovePlugin = require('remove-files-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const glob = require('glob');
const postcssRTL = require('postcss-rtl');

// Function to check for the existence of files matching a pattern
function hasFiles(pattern) {
	return glob.sync(pattern, { dotRelative: true }).length > 0;
}

// Dynamically generate entry points for each file inside 'assets/scss/blocks'
const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/core/*.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath.split(/[\\/]/).pop().replace('.scss', '');
		acc[`css/blocks/${entryKey}`] = filePath;
		return acc;
	}, {});

// Dynamically generate entry points for each block, including `view.js`
const blockEntryPaths = glob
	.sync('./assets/blocks/**/index.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace(new RegExp(`\\${path.sep}`, 'g'), '/')
			.replace('./assets/blocks/', '')
			.replace('/index.js', '');
		acc[`../blocks/${entryKey}/index`] = filePath;

		return acc;
	}, {});

// Include view.js files if they exist
const blockViewPaths = glob
	.sync('./assets/blocks/**/view.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace(new RegExp(`\\${path.sep}`, 'g'), '/')
			.replace('./assets/blocks/', '')
			.replace('/view.js', '');
		if (!filePath.includes('interactivity')) {
			acc[`../blocks/${entryKey}/view`] = filePath;
		}
		return acc;
	}, {});

const blockScssPaths = glob
	.sync('./assets/blocks/**/style.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace(new RegExp(`\\${path.sep}`, 'g'), '/')
			.replace('./assets/blocks/', '')
			.replace('/style.scss', '');
		acc[`../blocks/${entryKey}/style`] = filePath;
		return acc;
	}, {});

const styleScssPaths = glob
	.sync('./assets/scss/_index.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'style';
		acc[`css/${entryKey}`] = filePath;
		return acc;
	}, {});

// CopyPlugin patterns to include PHP and JSON files
const copyPluginPatterns = [];

// Only add PHP and JSON patterns if these files exist
if (hasFiles('./assets/blocks/**/*.php')) {
	copyPluginPatterns.push({
		from: './assets/blocks/**/*.php',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				path.resolve(context, 'assets/blocks') + path.sep,
				'../blocks/'
			);
		},
	});
}

if (hasFiles('./assets/blocks/**/view.js')) {
	copyPluginPatterns.push({
		from: './assets/blocks/**/view.js',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				path.resolve(context, 'assets/blocks') + path.sep,
				'../blocks/'
			);
		},
	});
}

if (hasFiles('./assets/blocks/**/*.json')) {
	copyPluginPatterns.push({
		from: './assets/blocks/**/*.json',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				path.resolve(context, 'assets/blocks') + path.sep,
				'../blocks/'
			);
		},
	});
}

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		admin: './assets/scss/editor.scss',
		index: './assets/js/index.js',
		variations: './assets/js/block-variations/index.js',
		filters: './assets/js/block-filters/index.js',
		...styleScssPaths,
		...blockEntryPaths,
		...blockViewPaths, // Add view.js paths here
		...blockScssPaths,
		...coreBlockEntryPaths,
	},
	output: {
		filename: (pathData) => {
			const entryName = pathData.chunk.name;
			if (
				entryName.includes('css/blocks') ||
				blockEntryPaths[entryName] ||
				blockScssPaths[entryName] ||
				blockViewPaths[entryName] // Make sure view.js is handled
			) {
				return '[name].js';
			}
			return 'js/[name].js';
		},
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
					{
						loader: 'postcss-loader',
						options: {
							postcssOptions: (loader) => {
								const options = {
									plugins: [require('autoprefixer')],
								};

								if (loader.filename) {
									const isRTL =
										loader.filename.includes('-rtl.css');
									if (isRTL) {
										options.plugins.push(postcssRTL());
									}
								}

								return options;
							},
						},
					},
					'sass-loader',
				],
			},
			{
				test: /\.svg$/,
				type: 'asset/inline',
				use: 'svg-transform-loader',
			},
			{
				test: /\.(woff|woff2|eot|ttf|otf)$/,
				type: 'asset/resource',
				generator: {
					filename: 'fonts/[name][ext]',
				},
			},
			{
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env', '@babel/preset-react'],
					},
				},
			},
		],
	},
	resolve: {
		preferRelative: true,
	},
	plugins: [
		...defaultConfig.plugins,

		new MiniCssExtractPlugin({
			filename: (pathData) => {
				const entryName = pathData.chunk.name;
				if (
					entryName.includes('css/blocks') ||
					blockEntryPaths[entryName] ||
					blockScssPaths[entryName] ||
					blockViewPaths[entryName] // Ensure view.css is handled
				) {
					return '[name].css';
				}
				if (entryName === 'css/style') {
					return 'css/style.css';
				}
				return '[name].css';
			},
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
				...copyPluginPatterns, // Include patterns for PHP and JSON files
			],
		}),

		new SVGSpritemapPlugin('assets/images/icons/*.svg', {
			output: {
				filename: 'images/icons/sprite.svg',
			},
			sprite: {
				prefix: false,
			},
		}),

		new RemovePlugin({
			after: {
				log: false,
				test: [
					{
						folder: path.resolve(__dirname, 'build/css/blocks'),
						method: (absoluteItemPath) => {
							return new RegExp(/\.js$/, 'm').test(
								absoluteItemPath
							);
						},
						// For .php
					},
					{
						folder: path.resolve(__dirname, 'build/css/blocks'),
						method: (absoluteItemPath) => {
							return new RegExp(/\.php$/, 'm').test(
								absoluteItemPath
							);
						},
					},
					{
						folder: path.resolve(__dirname, 'build/css/blocks'),
						method: (absoluteItemPath) => {
							return new RegExp(/view.js$/, 'm').test(
								absoluteItemPath
							);
						},
					},
				],
			},
		}),

		new CleanWebpackPlugin({
			cleanOnceBeforeBuildPatterns: [path.resolve(__dirname, 'build/**')],
		}),

		new ESLintPlugin({
			extensions: ['js', 'jsx'],
			exclude: 'node_modules',
		}),

		new StylelintPlugin({
			configFile: '.stylelintrc.json',
			files: '**/*.s?(a|c)ss',
		}),

		new TerserPlugin({
			terserOptions: {
				output: {
					comments: false,
				},
			},
			extractComments: false,
		}),
	],
	optimization: {
		minimize: true,
		minimizer: [
			new TerserPlugin({
				parallel: true,
				terserOptions: {
					output: {
						comments: false,
					},
				},
				extractComments: false,
			}),
		],
	},
};
