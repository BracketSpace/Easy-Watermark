/**
 * Internal dependencies
 */
import { TOGGLE_SAVING } from '../action-types';

/**
 * TOGGLE_SAVING action reducer
 *
 * @param  {mixed}  state  State.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function isSaving( state, action ) {
	if ( TOGGLE_SAVING === action.type ) {
		return ! state;
	}

	return state;
}
