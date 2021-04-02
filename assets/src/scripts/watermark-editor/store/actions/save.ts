/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { SAVE, TOGGLE_SAVING } from '../action-types';

/**
 * Save action.
 *
 * @return {Object} Action object.
 */
export function save() : Action{
	return {
		type: SAVE,
	};
}

/**
 * Toggle saving action.
 *
 * @return {Object} Action object.
 */
export function toggleSaving() : Action {
	return {
		type: TOGGLE_SAVING,
	};
}
