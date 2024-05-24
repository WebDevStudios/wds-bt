// eslint-disable-next-line import/no-extraneous-dependencies
const { merge } = require('webpack-merge');
const common = require('./webpack.config.js');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');

/**
 * Webpack config (Production mode)
 *
 * @see https://webpack.js.org/guides/production/
 */
module.exports = merge(common, {
	optimization: {
		minimizer: [
			/**
			 * Minify CSS.
			 *
			 * @see https://www.npmjs.com/package/css-minimizer-webpack-plugin
			 */
			new CssMinimizerPlugin({
				minimizerOptions: {
					preset: [
						'default',
						{
							discardComments: { removeAll: true },
						},
					],
				},
			}),
			new ImageMinimizerPlugin({
				minimizer: {
					implementation: ImageMinimizerPlugin.imageminMinify,
					options: {
						// Lossless optimization with custom option
						// Feel free to experiment with options for better result for you
						plugins: [
							['gifsicle', { interlaced: true }],
							['jpegtran', { progressive: true }],
							['optipng', { optimizationLevel: 5 }],
							// Svgo configuration here https://github.com/svg/svgo#configuration
							[
								'svgo',
								{
									plugins: [
										{
											name: 'preset-default',
											params: {
												overrides: {
													removeViewBox: false,
													addAttributesToSVGElement: {
														params: {
															attributes: [
																{
																	xmlns: 'http://www.w3.org/2000/svg',
																},
															],
														},
													},
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
});
