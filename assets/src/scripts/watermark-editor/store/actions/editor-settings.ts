/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

/**
 * Internal dependencies
 */
import { LOAD_EDITOR_SETTINGS, SAVE_EDITOR_SETTINGS } from '../action-types';
import { TEditorSettings } from 'types';

/**
 * Load editor settings action.
 *
 * @param  {Object} settings Editor settings.
 * @return {Object}          Action object.
 */
export function loadEditorSettings( settings: TEditorSettings ) : Action {
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
export function saveEditorSettings( settings: TEditorSettings ) : Action {
	return {
		type: SAVE_EDITOR_SETTINGS,
		settings,
	};
}
