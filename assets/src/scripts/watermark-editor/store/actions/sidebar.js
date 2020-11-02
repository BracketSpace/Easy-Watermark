/**
 * Internal dependencies
 */
import {
	TOGGLE_SIDEBAR,
	TOGGLE_SIDEBAR_TAB,
	TOGGLE_PANEL,
} from '../action-types';

/**
 * Open sidebar.
 */
export function openSidebar() {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: true,
	};
}

/**
 * Close sidebar.
 */
export function closeSidebar() {
	return {
		type: TOGGLE_SIDEBAR,
		isOpen: false,
	};
}

/**
 * @param tab
 */
export function toggleSidebarTab( tab ) {
	return {
		type: TOGGLE_SIDEBAR_TAB,
		tab,
	};
}

/**
 * @param panel
 */
export function togglePanel( panel ) {
	return {
		type: TOGGLE_PANEL,
		panel,
	};
}
