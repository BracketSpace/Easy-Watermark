const path = require( 'path' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const RemovePlugin = require( 'remove-files-webpack-plugin' );
const CopyPlugin = require( 'copy-webpack-plugin' );
const StyleLintPlugin = require( 'stylelint-webpack-plugin' );

module.exports = ( env, argv ) => {
	return {
		mode: argv.mode,
		entry: {
			dashboard: './assets/src/scripts/dashboard.js',
			uploader: './assets/src/scripts/uploader.js',
			'attachment-edit': './assets/src/scripts/attachment-edit.js',
			'media-library': './assets/src/scripts/media-library.js',
			'watermark-edit': './assets/src/scripts/watermark-edit.js',
		},
		output: {
			path: path.resolve( __dirname, 'assets/dist' ),
			filename: './scripts/[name].js',
			publicPath: '../',
		},
		performance: {
			hints: false,
			maxEntrypointSize: 512000,
			maxAssetSize: 512000,
		},
		devtool: 'development' === argv.mode ? 'source-maps' : false,
		module: {
			rules: [
				...( ! argv.watch ? [
					{
						enforce: 'pre',
						test: /\.js$/,
						exclude: /node_modules/,
						loader: 'eslint-loader',
						options: {
							configFile: ( 'production' === argv.mode ) ? '.eslintrc.prod' : '.eslintrc',
						},
					},
				] : [] ),
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
					},
				},
				{
					test: /\.css$/,
					loader: 'style-loader!css-loader',
				},
				{
					test: /\.scss$/,
					use: ExtractTextPlugin.extract( {
						fallback: 'style-loader',
						use: [
							'css-loader',
							'sass-loader',
						],
					} ),
				},
				{
					test: /\.(png|jpg|gif)$/i,
					use: [
						{
							loader: 'url-loader',
							options: {
								limit: 8192,
								name: '[name].[ext]',
								outputPath: 'images',
							},
						},
					],
				},
				{
					test: /\.(woff(2)?|ttf|eot|svg)$/,
					use: [
						{
							loader: 'file-loader',
							options: {
								limit: 8192,
								name: '[name].[ext]',
								outputPath: 'fonts',
							},
						},
					],
				},
			],
		},
		plugins: [
			new ExtractTextPlugin( 'styles/[name].css' ),
			new CopyPlugin( [
				{ from: 'assets/src/fonts', to: 'fonts' },
			] ),
			new RemovePlugin( {
				before: {
					include: [ 'assets/dist' ],
				},
			} ),
			...( ! argv.watch ? [
				new StyleLintPlugin( {
					configFile: '.stylelintrc',
					context: 'assets/src/styles',
					// quiet: false,
				} ),
			] : [] ),
		],
		externals: {
			jquery: 'jQuery',
			backbone: 'Backbone',
		},
	};
};
