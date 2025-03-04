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
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
const glob = require('glob');
const postcssRTL = require('postcss-rtl');

// Function to check for the existence of files matching a pattern
function hasFiles(pattern) {
	return glob.sync(pattern, { dotRelative: true }).length > 0;
}

// Remove all files from the build directory except for the specified folders.
const excludedFolders = ['css', 'js', 'fonts', 'images'];

// Dynamically generate entry points for each file inside 'assets/scss/blocks/core'
const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/core/*.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath.split(/[\\/]/).pop().replace('.scss', '');
		acc[`css/blocks/${entryKey}`] = filePath;
		return acc;
	}, {});

// Dynamically generate entry points for each file inside 'assets/scss/blocks/third-party'
const thirdPartyBlockEntryPaths = glob
	.sync('./assets/scss/blocks/third-party/*.scss', { dotRelative: true })
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

const editorScssPaths = glob
	.sync('./assets/scss/editor.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'editor';
		acc[`css/${entryKey}`] = filePath;
		return acc;
	}, {});

// CopyPlugin patterns to include PHP, JSON and image files
const copyPluginPatterns = [];

if (hasFiles('./assets/blocks/**/*.{php,json}')) {
	copyPluginPatterns.push({
		from: './assets/blocks/**/*.{php,json}',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				path.resolve(context, 'assets/blocks') + path.sep,
				'../blocks/'
			);
		},
	});
}

if (hasFiles('./assets/blocks/**/*.{png,jpg,jpeg,gif,svg,webp}')) {
	copyPluginPatterns.push({
		from: './assets/blocks/**/*.{png,jpg,jpeg,gif,svg,webp}',
		to: ({ context, absoluteFilename }) => {
			return absoluteFilename.replace(
				path.resolve(context, 'assets/blocks') + path.sep,
				'../blocks/'
			);
		},
		noErrorOnMissing: true,
	});
}

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		editor: './assets/js/editor.js',
		index: './assets/js/index.js',
		variations: './assets/js/block-variations/index.js',
		filters: './assets/js/block-filters/index.js',
		...styleScssPaths,
		...editorScssPaths,
		...blockEntryPaths,
		...blockViewPaths,
		...blockScssPaths,
		...coreBlockEntryPaths,
		...thirdPartyBlockEntryPaths,
	},
	output: {
		filename: (pathData) => {
			const entryName = pathData.chunk.name;
			if (
				entryName.includes('css/blocks') ||
				blockEntryPaths[entryName] ||
				blockScssPaths[entryName] ||
				blockViewPaths[entryName]
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
					blockViewPaths[entryName]
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
				...copyPluginPatterns,
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
						method: (absoluteItemPath) =>
							/\.php$/.test(absoluteItemPath),
						recursive: true,
					},
					{
						folder: path.resolve(__dirname, 'build/css'),
						method: (absoluteItemPath) =>
							/\.js$/.test(absoluteItemPath),
						recursive: true,
					},
					{
						folder: path.resolve(__dirname, 'build/js'),
						method: (absoluteItemPath) =>
							!/\.js$/.test(absoluteItemPath),
						recursive: true,
					},
					{
						folder: path.resolve(__dirname, 'build'),
						method: (absoluteItemPath) => {
							return !excludedFolders.some((folder) =>
								absoluteItemPath.includes(
									path.resolve(__dirname, `build/${folder}`)
								)
							);
						},
						recursive: true,
					},
					{
						folder: path.resolve(__dirname, 'blocks'),
						method: (absoluteItemPath) =>
							/\.asset\.php$/.test(absoluteItemPath),
						recursive: true,
					},
				],
			},
		}),

		new CleanWebpackPlugin({
			cleanOnceBeforeBuildPatterns: [path.resolve(__dirname, 'build/**')],
		}),

		new ESLintPlugin({
			configType: 'eslintrc',
			extensions: ['js', 'jsx'],
			exclude: 'node_modules',
			eslintPath: require.resolve('eslint/use-at-your-own-risk'),
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
			new ImageMinimizerPlugin({
				minimizer: {
					implementation: ImageMinimizerPlugin.imageminGenerate,
					options: {
						plugins: [
							['gifsicle', { interlaced: true }],
							['jpegtran', { progressive: true }],
							['optipng', { optimizationLevel: 5 }],
							[
								'svgo',
								{
									plugins: [
										{
											name: 'preset-default',
											params: {
												overrides: {
													removeViewBox: false,
												},
											},
										},
									],
								},
							],
						],
					},
				},
			}),
		],
	},
	performance: {
		maxAssetSize: 500000,
		hints: 'warning',
	},
};
