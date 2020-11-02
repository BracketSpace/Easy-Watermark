/**
 * Internal dependencies
 */
import { TOGGLE_FEATURE } from '../action-types';

/**
 * @param state
 * @param action
 */
export function features( state, action ) {
	if ( TOGGLE_FEATURE === action.type ) {
		const isFeatureOn = action.isFeatureOn ? action.isFeatureOn : ! state[ action.feature ];

		return {
			...state,
			[ action.feature ]: isFeatureOn,
		};
	}

	return state;
}
