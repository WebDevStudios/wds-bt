const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const RemovePlugin = require('remove-files-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const ESLintPlugin = require('eslint-webpack-plugin');
const StylelintPlugin = require('stylelint-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const glob = require('glob');
const postcssRTL = require('postcss-rtl');

function hasFiles(pattern) {
	return glob.sync(pattern, { dotRelative: true }).length > 0;
}
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

const blockViewPaths = glob
	.sync('./assets/blocks/**/view.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath
			.replace(new RegExp(`\\${path.sep}`, 'g'), '/')
			.replace('./assets/blocks/', '')
			.replace('/view.js', '');
		acc[`../blocks/${entryKey}/view`] = filePath;
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

const coreBlockEntryPaths = glob
	.sync('./assets/scss/blocks/core/*.scss', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = filePath.split(/[\\/]/).pop().replace('.scss', '');
		acc[`css/blocks/${entryKey}`] = filePath;
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

const scriptJsPaths = glob
	.sync('./assets/js/index.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'index';
		acc[`js/${entryKey}`] = filePath;
		return acc;
	}, {});

const filtersJsPaths = glob
	.sync('./assets/js/block-filters/index.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'filters';
		acc[`js/${entryKey}`] = filePath;
		return acc;
	}, {});

const variationsJsPaths = glob
	.sync('./assets/js/block-variations/index.js', { dotRelative: true })
	.reduce((acc, filePath) => {
		const entryKey = 'variations';
		acc[`js/${entryKey}`] = filePath;
		return acc;
	}, {});

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

copyPluginPatterns.push({
	from: './assets/blocks/**/*.{png,jpg,jpeg,gif,svg,webp}',
	to: ({ context, absoluteFilename }) => {
		return absoluteFilename.replace(
			path.resolve(context, 'assets/blocks') + path.sep,
			'../blocks/'
		);
	},
});

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		...scriptJsPaths,
		...variationsJsPaths,
		...filtersJsPaths,
		...editorScssPaths,
		...styleScssPaths,
		...blockEntryPaths,
		...blockViewPaths,
		...blockScssPaths,
		...coreBlockEntryPaths,
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
				test: /\.(js|jsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env', '@babel/preset-react'],
						plugins: ['@babel/plugin-transform-react-jsx'],
					},
				},
			},
		],
	},
	output: {
		filename: '[name].js',
		path: path.resolve(__dirname, 'build'),
	},
	plugins: [
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
					},
					{
						folder: path.resolve(__dirname, 'build/css'),
						method: (absoluteItemPath) => {
							return new RegExp(/\.js$/, 'm').test(
								absoluteItemPath
							);
						},
					},
				],
			},
		}),
		new SVGSpritemapPlugin('assets/images/icons/*.svg', {
			output: { filename: 'images/icons/sprite.svg' },
			sprite: { prefix: false },
		}),
		new MiniCssExtractPlugin({ filename: '[name].css' }),
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
		minimizer: [new TerserPlugin()],
	},
	resolve: {
		extensions: ['.js', '.jsx'],
	},
};
