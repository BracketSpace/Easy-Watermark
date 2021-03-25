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
 * @return {Object} Action object
 */
export function openSidebar() {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: true,
	};
}

/**
 * Close sidebar action
 *
 * @return {Object} Action object
 */
export function closeSidebar() {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: false,
	};
}

/**
 * Toggle sidebar tab action
 *
 * @param  {string} tab Tab key to open.
 * @return {Object}     Action object.
 */
export function toggleSidebarTab( tab ) {
	return {
		type: TOGGLE_SIDEBAR_TAB,
		tab,
	};
}

/**
 * Toggle sidebar panel action.
 *
 * @param  {string} panel Panel key to open.
 * @return {Object}       Action object.
 */
export function togglePanel( panel ) {
	return {
		type: TOGGLE_PANEL,
		panel,
	};
}
