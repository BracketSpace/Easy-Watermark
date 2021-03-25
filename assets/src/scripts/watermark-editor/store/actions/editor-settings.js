/**
 * Internal dependencies
 */
import { LOAD_EDITOR_SETTINGS, SAVE_EDITOR_SETTINGS } from '../action-types';

/**
 * Load editor settings action.
 *
 * @param  {Object} settings Editor settings.
 * @return {Object}          Action object.
 */
export function loadEditorSettings( settings ) {
	return {
		type: LOAD_EDITOR_SETTINGS,
		settings,
	};
}

/**
 * Save editor settings action.
 *
 * @param  {Object} settings Editor settings.
 * @return {Object}          Action object.
 */
export function saveEditorSettings( settings ) {
	return {
		type: SAVE_EDITOR_SETTINGS,
		settings,
	};
}
