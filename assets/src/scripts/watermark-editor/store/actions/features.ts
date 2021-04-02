/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

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
export function toggleFeature( feature: string ) : Action {
	return {
		type: TOGGLE_FEATURE,
		feature,
	};
}
