/**
 * Check if sidebar is open
 *
 * @param  {Object}  state State object.
 * @return {boolean}       Whether sidebar is open.
 */
export function isSidebarOpened( state ) {
	return state.isSidebarOpened;
}

/**
 * Get active sidebar tab
 *
 * @param  {Object} state State object.
 * @return {string}       Active sidebar tab.
 */
export function getActiveSidebarTab( state ) {
	return state.activeSidebarTab;
}

/**
 * Determine if given panel is open
 *
 * @param  {Object}  state State object.
 * @param  {string}  panel Panel name.
 * @return {boolean}       Whether the panel is open.
 */
export function isPanelOpen( state, panel ) {
	return !! state.panels[ panel ];
}
