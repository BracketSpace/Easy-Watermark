/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import {
	loadWatermarkSuccess,
	loadWatermarkError,
} from '../actions';

/**
 * @param action
 */
export default function( action, { dispatch } ) {
	apiFetch( {
		path: `/wp/v2/watermarks/${ action.watermarkID }?context=edit`,
	} ).then( ( watermark ) => {
		dispatch( loadWatermarkSuccess( watermark, true ) );
	} ).catch( ( error ) => {
		dispatch( loadWatermarkError( error ) );
	} );
}
