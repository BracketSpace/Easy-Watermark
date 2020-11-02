/**
 * Internal dependencies
 */
import {
	LOAD_EDITOR_SETTINGS,
	SAVE_EDITOR_SETTINGS,
} from '../action-types';

/**
 * @param settings
 */
export function loadEditorSettings( settings ) {
	return {
		type: LOAD_EDITOR_SETTINGS,
		settings,
	};
}

/**
 * @param settings
 */
export function saveEditorSettings( settings ) {
	return {
		type: SAVE_EDITOR_SETTINGS,
		settings,
	};
}
