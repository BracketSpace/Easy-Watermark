/**
 * Internal dependencies
 */
import {
	TOGGLE_FEATURE,
} from '../action-types';

/**
 * @param feature
 */
export function toggleFeature( feature ) {
	return {
		type: TOGGLE_FEATURE,
		feature,
	};
}
