/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Internal dependencies
 */
import {
	toggleSaving,
	loadWatermarkSuccess,
} from '../actions';
import { getEditedData } from '../selectors';

export default ( action, { getState, dispatch } ) => {
	dispatch( toggleSaving() );

	const state = getState();

	state.editedData.status = 'publish';

	apiFetch( {
		method: 'POST',
		path: `/wp/v2/watermarks/${ state.initialData.id }?context=edit`,
		data: getEditedData( state ),
	} ).then( ( response ) => {
		dispatch( loadWatermarkSuccess( response ) );
	} ).catch( () => {
		// TODO: error handling.
	} ).finally( () => {
		dispatch( toggleSaving() );
	} );
};
