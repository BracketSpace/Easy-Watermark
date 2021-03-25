/**
 * Internal dependencies
 */
import { SAVE, TOGGLE_SAVING } from '../action-types';

/**
 * Save action.
 *
 * @return {Object} Action object.
 */
export function save() {
	return {
		type: SAVE,
	};
}

/**
 * Toggle saving action.
 *
 * @return {Object} Action object.
 */
export function toggleSaving() {
	return {
		type: TOGGLE_SAVING,
	};
}
