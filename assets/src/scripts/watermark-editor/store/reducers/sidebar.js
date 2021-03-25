/**
 * Internal dependencies
 */
import {
	TOGGLE_SIDEBAR,
	TOGGLE_SIDEBAR_TAB,
	TOGGLE_PANEL,
} from '../action-types';

/**
 * TOGGLE_SIDEBAR action reducer
 *
 * @param  {mixed}  state  Current state.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function isSidebarOpened( state, action ) {
	if ( TOGGLE_SIDEBAR === action.type ) {
		return action.isOpen;
	}

	return state;
}

/**
 * TOGGLE_SIDEBAR_TAB action reducer
 *
 * @param  {mixed}  state  Current state.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function activeSidebarTab( state, action ) {
	if ( TOGGLE_SIDEBAR_TAB === action.type ) {
		return action.tab;
	}

	return state;
}

/**
 * TOGGLE_PANEL action reducer
 *
 * @param  {mixed}  state  Current state.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
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
