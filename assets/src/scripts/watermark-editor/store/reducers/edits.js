/**
 * Internal dependencies
 */
import { CREATE_OBJECT, EDIT_OBJECT } from '../action-types';

/**
 * External dependencies
 */
import { isEmpty } from 'lodash';

/**
 * Reducer for CREATE_OBJECT and EDIT_OBJECT actions.
 *
 * @param  {mixed}  state  State.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function edits( state, action ) {
	let current = false;

	switch ( action.type ) {
		case CREATE_OBJECT:
			current = {
				[ action.object.id ]: action.object,
			};
			break;
		case EDIT_OBJECT:
			current = {
				[ action.id ]: action.data,
			};
			break;
	}

	if ( current ) {
		const result = {
			past: [ ...state.past ],
			current,
			future: [],
		};

		if ( ! isEmpty( state.current ) ) {
			result.past.push( state.current );
		}

		return result;
	}

	return state;
}
