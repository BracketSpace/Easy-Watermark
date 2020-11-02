/**
 * Internal dependencies
 */
import {
	TOGGLE_SIDEBAR,
	TOGGLE_SIDEBAR_TAB,
	TOGGLE_PANEL,
} from '../action-types';

/**
 * @param state
 * @param action
 */
export function isSidebarOpened( state, action ) {
	if ( TOGGLE_SIDEBAR === action.type ) {
		return action.isOpen;
	}

	return state;
}

/**
 * @param state
 * @param action
 */
export function activeSidebarTab( state, action ) {
	if ( TOGGLE_SIDEBAR_TAB === action.type ) {
		return action.tab;
	}

	return state;
}

/**
 * @param state
 * @param action
 */
export function panels( state, action ) {
	if ( TOGGLE_PANEL === action.type ) {
		const isOpen = !! state[ action.panel ];

		return {
			...state,
			[ action.panel ]: ! isOpen,
		};
	}

	return state;
}
