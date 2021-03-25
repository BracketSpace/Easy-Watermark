/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

/**
 * Side effect for loading editor settings
 *
 * @param  {Object} action Action object.
 * @return {void}
 */
export default function ( action ) {
	apiFetch( {
		path: `/${ ew.namespace }/v1/editor-settings`,
		method: 'POST',
		data: action.settings,
	} ).catch( () => {
		// Fake function to catch errors.
	} );
}
