/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * @param action
 */
export default function( action ) {
	apiFetch( {
		path: `/${ ew.namespace }/v1/editor-settings`,
		method: 'POST',
		data: action.settings,
	} ).catch( () => {
		// Fake function to catch errors.
	} );
}
