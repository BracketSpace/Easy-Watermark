/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import { loadWatermarkSuccess, loadWatermarkError } from '../actions';

/**
 * Side effect to load watermark data.
 *
 * @param  {Object} action   Action object.
 * @param  {Object} store    Store object..
 * @param  {Function} store.dispatch dispatch method.
 * @return {void}
 */
export default function ( action, { dispatch } ) {
	apiFetch( {
		path: `/wp/v2/watermarks/${ action.watermarkID }?context=edit`,
	} )
		.then( ( watermark ) => {
			dispatch( loadWatermarkSuccess( watermark, true ) );
		} )
		.catch( ( error ) => {
			dispatch( loadWatermarkError( error ) );
		} );
}
