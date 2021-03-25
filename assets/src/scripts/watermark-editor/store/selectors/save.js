/**
 * External dependencies
 */
import { isEqual } from 'lodash';

/**
 * Internal dependencies
 */
import { getEditedData } from './data';

/**
 * Determine if the data is savable
 *
 * @param  {Object}  state State object.
 * @return {boolean}       Whether can perform save operation.
 */
export function isSaveable( state ) {
	if ( isSaving( state ) ) {
		return false;
	}

	const editedData = getEditedData( state );

	return !! editedData.title && ! isEqual( state.initialData, editedData );
}

/**
 * Detemine if the editor is saving
 *
 * @param  {Object}  state State object.
 * @return {boolean}       Whether is saving.
 */
export function isSaving( state ) {
	return state.isSaving;
}
