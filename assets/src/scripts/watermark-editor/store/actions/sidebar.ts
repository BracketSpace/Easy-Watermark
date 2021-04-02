/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

/**
 * Internal dependencies
 */
import {
	TOGGLE_SIDEBAR,
	TOGGLE_SIDEBAR_TAB,
	TOGGLE_PANEL,
} from '../action-types';

/**
 * Open sidebar action
 *
 * @return Action object
 */
export function openSidebar() : Action {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: true,
	};
}

/**
 * Close sidebar action
 *
 * @return Action object
 */
export function closeSidebar() : Action {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: false,
	};
}

/**
 * Toggle sidebar tab action
 *
 * @param  tab Tab key to open.
 * @return     Action object.
 */
export function toggleSidebarTab( tab: string ) : Action {
	return {
		type: TOGGLE_SIDEBAR_TAB,
		tab,
	};
}

/**
 * Toggle sidebar panel action.
 *
 * @param  panel Panel key to open.
 * @return       Action object.
 */
export function togglePanel( panel: string ) : Action {
	return {
		type: TOGGLE_PANEL,
		panel,
	};
}
