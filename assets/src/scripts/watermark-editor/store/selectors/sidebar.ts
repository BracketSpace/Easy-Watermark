/**
 * Internal dependencies
 */
import { TStoreState } from 'types';

/**
 * Check if sidebar is open
 *
 * @param  state State object.
 * @return       Whether sidebar is open.
 */
export function isSidebarOpened( state: TStoreState ) : boolean {
	return state.isSidebarOpened;
}

/**
 * Get active sidebar tab
 *
 * @param  state State object.
 * @return       Active sidebar tab.
 */
export function getActiveSidebarTab( state: TStoreState ) : string {
	return state.activeSidebarTab;
}

/**
 * Determine if given panel is open
 *
 * @param  state State object.
 * @param  panel Panel name.
 * @return       Whether the panel is open.
 */
export function isPanelOpen( state: TStoreState, panel: string ) : boolean {
	return !! state.panels[ panel ];
}
