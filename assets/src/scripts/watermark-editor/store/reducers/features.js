/**
 * Internal dependencies
 */
import { TOGGLE_FEATURE } from '../action-types';

/**
 * TOGGLE_FEATURE action reducer
 *
 * @param  {mixed}  state  State.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function features( state, action ) {
	if ( TOGGLE_FEATURE === action.type ) {
		const isFeatureOn = action.isFeatureOn
			? action.isFeatureOn
			: ! state[ action.feature ];

		return {
			...state,
			[ action.feature ]: isFeatureOn,
		};
	}

	return state;
}
