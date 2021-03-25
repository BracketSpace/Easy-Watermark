/**
 * Internal dependencies
 */
import { TOGGLE_FEATURE } from '../action-types';

/**
 * Toggle feature action.
 *
 * @param  {string} feature Feature key to toggle.
 * @return {Object}         Action object.
 */
export function toggleFeature( feature ) {
	return {
		type: TOGGLE_FEATURE,
		feature,
	};
}
