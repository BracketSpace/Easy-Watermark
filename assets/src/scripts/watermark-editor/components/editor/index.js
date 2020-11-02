/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { withDispatch } from '@wordpress/data';

/**
 * Internal dependencies
 */
import Layout from '../editor-layout';

class Editor extends Component {
	constructor( props ) {
		super( ...arguments );

		const {
			loadEditorSettings,
			loadWatermarkData,
			watermarkID,
			settings,
		} = props;

		loadEditorSettings( settings );
		loadWatermarkData( watermarkID );
	}

	render() {
		return (
			<Layout />
		);
	}
}

export default withDispatch( ( dispatch ) => {
	const {
		loadEditorSettings,
		loadWatermarkData,
	} = dispatch( 'easy-watermark' );

	return {
		loadEditorSettings,
		loadWatermarkData,
	};
} )( Editor );
