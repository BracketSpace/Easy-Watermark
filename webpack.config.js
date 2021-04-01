const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const CopyPlugin = require( 'copy-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const FriendlyErrorsWebpackPlugin = require( 'friendly-errors-webpack-plugin' );
const ESLintPlugin = require( 'eslint-webpack-plugin' );
const path = require( 'path' );
const StyleLintPlugin = require( 'stylelint-webpack-plugin' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const ForkTsCheckerWebpackPlugin = require( 'fork-ts-checker-webpack-plugin' );
const {
	defaultRequestToExternal,
	defaultRequestToHandle,
} = require( '@wordpress/dependency-extraction-webpack-plugin/lib/util' );
const globImporter = require( 'node-sass-glob-importer' );
const { lstatSync, readdirSync } = require( 'fs' );

const srcPath = 'assets/src/scripts';
const entry = {};

const BUNDLED_PACKAGES = [ '@wordpress/compose' ];

readdirSync( srcPath ).forEach( ( file ) => {
	const filePath = path.join( srcPath, file );

	if ( lstatSync( filePath ).isFile() ) {
		const ext = path.extname( file );
		const name = path.basename( file, ext );

		entry[ name ] = path.resolve( __dirname, filePath );
	}
} );

module.exports = ( env, argv ) => {
	return {
		mode: argv.mode,
		entry,
		output: {
			path: path.resolve( __dirname, 'assets/dist' ),
			filename: './scripts/[name].js',
			publicPath: '../',
		},
		devtool: 'development' === argv.mode ? 'source-map' : false,
		performance: {
			hints: false,
			maxEntrypointSize: 512000,
			maxAssetSize: 512000,
		},
		module: {
			rules: [
				{
					test: /\.[jt]sx?$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
						options: {
							presets: [ '@babel/preset-env' ],
						},
					},
				},
				{
					test: /\.css$/,
					use: [ 'style-loader', 'css-loader' ],
				},
				{
					test: /\.scss$/,
					use: [
						MiniCssExtractPlugin.loader,
						'css-loader',
						{
							loader: 'sass-loader',
							options: {
								sassOptions: {
									importer: globImporter(),
								},
							},
						},
					],
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
					test: /\.svg$/,
					use: [ '@svgr/webpack' ],
				},
				{
					test: /\.(woff(2)?|ttf|eot)$/,
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
		resolve: {
			extensions: [ '.js', '.jsx', '.ts', '.tsx' ],
			alias: {
				images: path.resolve( __dirname, 'assets/src/images' ),
			},
			modules: [
				path.resolve( __dirname + '/assets/src/scripts' ),
				path.resolve( __dirname + '/node_modules' ),
			],
		},
		plugins: [
			new CleanWebpackPlugin(),
			new CopyPlugin( {
				patterns: [ { from: 'assets/src/fonts', to: 'fonts' } ],
			} ),
			new DependencyExtractionWebpackPlugin( {
				injectPolyfill: true,
				useDefaults: false,
				requestToExternal: ( request ) => {
					if ( BUNDLED_PACKAGES.includes( request ) ) {
						return undefined;
					}

					return defaultRequestToExternal( request );
				},
				requestToHandle: defaultRequestToHandle,
			} ),
			new MiniCssExtractPlugin( {
				filename: 'styles/[name].css',
			} ),
			new FriendlyErrorsWebpackPlugin(),
			new ForkTsCheckerWebpackPlugin( {
				async: !! argv.watch,
			} ),
			...( ! argv.watch
				? [
						new StyleLintPlugin( {
							configFile: '.stylelintrc',
							context: 'assets/src/styles',
						} ),
						new ESLintPlugin( {
							overrideConfigFile:
								'production' === argv.mode
									? '.eslintrc.prod.json'
									: '.eslintrc.json',
						} ),
				  ]
				: [] ),
		],
		externals: {
			backbone: 'Backbone',
		},
	};
};
