/**
 * Internal dependencies
 */
import {
	SAVE,
	TOGGLE_SAVING,
} from '../action-types';

/**
 * Save.
 */
export function save() {
	return {
		type: SAVE,
	};
}

/**
 * Toggle saving.
 */
export function toggleSaving() {
	return {
		type: TOGGLE_SAVING,
	};
}
