const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemovePlugin = require('remove-files-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const glob = require('glob');
const postcssRTL = require('postcss-rtl');

// Dynamically generate entry points for each file inside 'assets/scss/blocks'
const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/**/*.scss', {
		posix: true,
		dotRelative: true,
	})
	.reduce((acc, filePath) => {
		const entryKey = filePath.split(/[\\/]/).pop().replace('.scss', '');
		acc[`css/blocks/${entryKey}`] = filePath;
		return acc;
	}, {});

// Dynamically generate entry points for each block
const blockEntryPaths = glob
	.sync('./assets/blocks/**/index.js', { posix: true, dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace('./assets/blocks/', '')
			.replace('/index.js', '');
		acc[`../blocks/${entryKey}/index`] = filePath;
		return acc;
	}, {});

const blockScssPaths = glob
	.sync('./assets/blocks/**/style.scss', { posix: true, dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace('./assets/blocks/', '')
			.replace('/style.scss', '');
		acc[`../blocks/${entryKey}/style`] = filePath;
		return acc;
	}, {});

const styleScssPaths = glob
	.sync('./assets/scss/index.scss', { posix: true, dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'style';
		acc[`css/${entryKey}`] = filePath;
		return acc;
	}, {});

// CopyPlugin patterns to include PHP and JSON files
const copyPluginPatterns = [
	{
		from: './assets/blocks/**/*.php',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				`${context}/assets/blocks/`,
				'../blocks/'
			);
		},
	},
	{
		from: './assets/blocks/**/*.json',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				`${context}/assets/blocks/`,
				'../blocks/'
			);
		},
	},
];

module.exports = {
	...defaultConfig,
	entry: {
		index: './assets/js/index.js',
		variations: './assets/js/block-variations/index.js',
		filters: './assets/js/block-filters/index.js',
		...styleScssPaths,
		...blockEntryPaths,
		...blockScssPaths,
		...coreBlockEntryPaths,
	},
	output: {
		filename: (pathData) => {
			const entryName = pathData.chunk.name;
			if (
				entryName.includes('css/blocks') ||
				blockEntryPaths[entryName] ||
				blockScssPaths[entryName]
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
				test: /\.js$/,
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
	plugins: [
		...defaultConfig.plugins,

		new MiniCssExtractPlugin({
			filename: (pathData) => {
				const entryName = pathData.chunk.name;
				if (
					entryName.includes('css/blocks') ||
					blockEntryPaths[entryName] ||
					blockScssPaths[entryName]
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
						folder: path.resolve(__dirname, 'build'),
						method: (absoluteItemPath) => {
							return new RegExp(/\.js/, 'm').test(
								absoluteItemPath
							);
						},
					},
					{
						folder: path.resolve(__dirname, 'build'),
						method: (absoluteItemPath) => {
							return new RegExp(/\.php$/, 'm').test(
								absoluteItemPath
							);
						},
					},
				],
			},
		}),

		new CleanWebpackPlugin(),
		new ESLintPlugin(),
		new StylelintPlugin(),
	],
	performance: {
		maxAssetSize: 550000, // Increase the asset size limit to 550 KB
		maxEntrypointSize: 550000, // Increase the entry point size limit to 550 KB
		hints: 'warning', // You can set this to 'error' to make the build fail on these warnings or 'false' to disable them
	},
};
