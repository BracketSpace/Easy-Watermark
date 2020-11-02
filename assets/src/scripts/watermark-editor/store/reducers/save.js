/**
 * Internal dependencies
 */
import {
	TOGGLE_SAVING,
} from '../action-types';

/**
 * @param state
 * @param action
 */
export function isSaving( state, action ) {
	if ( TOGGLE_SAVING === action.type ) {
		return ! state;
	}

	return state;
}
