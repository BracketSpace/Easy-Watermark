/**
 * This file is needed for the eslint-plugin-import package. It defines 'jquery'
 * as external package to suppress no-unresolved errors. This can't be done in
 * the main webpack config file because the DependencyExtractionWebpackPlugin is
 * used to handle external WordPress dependencies and Webpack's `externals`
 * config may confilict with it.
 */

const baseConfigCallable = require( './webpack.config.js' );

module.exports = ( env, argv ) => {
	const baseConfig = baseConfigCallable( env, argv );

	return {
		...baseConfig,
		externals: {
			...baseConfig.externals,
			jquery: 'jQuery',
		},
	};
};
