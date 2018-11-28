const path = require('path')
const webpack = require('webpack')
const ExtractTextPlugin = require("extract-text-webpack-plugin")

module.exports = (env, argv) => {
	return {
	  entry: "./assets/src/main.js",
	  output: {
			path: path.resolve(__dirname, "assets/dist"),
	    filename: "./scripts/easy-watermark.js"
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
				}
			]
		},
		plugins: [
			new ExtractTextPlugin('styles/easy-watermark.css'),
		],
		externals: {
			jquery: 'jQuery'
		}
	}
}
