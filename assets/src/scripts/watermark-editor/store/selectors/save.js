/**
 * External dependencies
 */
import { isEqual } from 'lodash';

/**
 * Internal dependencies
 */
import { getEditedData } from './data';

/**
 * @param state
 */
export function isSaveable( state ) {
	if ( isSaving( state ) ) {
		return false;
	}

	const editedData = getEditedData( state );

	return !! editedData.title && ! isEqual( state.initialData, editedData );
}

/**
 * @param state
 */
export function isSaving( state ) {
	return state.isSaving;
}
