/**
 * @param state
 */
export function isSidebarOpened( state ) {
	return state.isSidebarOpened;
}

/**
 * @param state
 */
export function getActiveSidebarTab( state ) {
	return state.activeSidebarTab;
}

/**
 * @param state
 * @param panel
 */
export function isPanelOpen( state, panel ) {
	return !! state.panels[ panel ];
}
