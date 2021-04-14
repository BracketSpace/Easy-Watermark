/**
 * External dependencies
 */
import { isEqual } from 'lodash';

/**
 * Internal dependencies
 */
import { getEditedData } from './data';
import { TStoreState } from 'types';

/**
 * Determine if the data is savable
 *
 * @param  {Object}  state State object.
 * @return {boolean}       Whether can perform save operation.
 */
export function isSaveable( state: TStoreState ) : boolean {
	if ( isSaving( state ) ) {
		return false;
	}

	const editedData = getEditedData( state );

	return !! editedData.title && ! isEqual( state.initialData, editedData );
}

/**
 * Detemine if the editor is saving
 *
 * @param  state State object.
 * @return       Whether is saving.
 */
export function isSaving( state: TStoreState ) : boolean {
	return state.isSaving;
}
