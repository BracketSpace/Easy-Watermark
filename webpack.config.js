const path = require('path')
const webpack = require('webpack')
const ExtractTextPlugin = require("extract-text-webpack-plugin")
const RemovePlugin = require('remove-files-webpack-plugin')
const CopyPlugin = require('copy-webpack-plugin')

module.exports = (env, argv) => {
	return {
		entry: {
			'main': './assets/src/styles/main.scss',
			'attachment-edit': './assets/src/scripts/attachment-edit.js',
			'settings': './assets/src/scripts/settings.js',
			'upload': './assets/src/scripts/upload.js',
			'watermark-edit': './assets/src/scripts/watermark-edit.js',
		},
	  output: {
			path: path.resolve(__dirname, "assets/dist"),
	    filename: "./scripts/[name].js",
			publicPath: '../'
	  },
		performance: {
			hints: false,
			maxEntrypointSize: 512000,
			maxAssetSize: 512000
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: "babel-loader"
					}
				},
				{
					test: /\.scss$/,
					use: ExtractTextPlugin.extract({
	          fallback: 'style-loader',
	          use: [
							'css-loader',
							'sass-loader'
						]
	        })
				},
				{
					test: /\.(png|jpg|gif)$/i,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 8192,
          			name: '[name].[ext]',
								outputPath: 'images'
							}
						}
					]
				},
				{
					test: /\.(woff(2)?|ttf|eot|svg)$/,
					use: [
						{
							loader: 'file-loader',
							options: {
								limit: 8192,
								name: '[name].[ext]',
								outputPath: 'fonts'
							}
						}
					]
				}
			]
		},
		plugins: [
			new ExtractTextPlugin('styles/easy-watermark.css'),
			new CopyPlugin([
	      { from: 'assets/src/fonts', to: 'fonts' },
	    ]),
			new RemovePlugin({
				before: {
					include: ['assets/dist']
				},
				after: {
					include: ['assets/dist/scripts/main.js']
				}
			})
		],
		externals: {
			jquery: 'jQuery'
		}
	}
}
