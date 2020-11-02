/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	NavigableToolbar,
} from '@wordpress/block-editor';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import {
	__experimentalToolbarItem as ExperimentalToolbarItem,
	Button,
	ToolbarItem,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import Inserter from './inserter';
import SaveButton from './save-button';
import FullscreenToggle from './fullscreen-toggle';
import FullscreenModeClose from './fullscreen-mode-close';

import shortcuts from '../../keyboard-shortcuts';

const Header = ( {
	openSidebar,
	closeSidebar,
	isSidebarOpened,
} ) => {
	const toggleSidebar = isSidebarOpened ? closeSidebar : openSidebar;

	const ToolbarItemComponent = ToolbarItem || ExperimentalToolbarItem;

	return (
		<div className="edit-post-header">
			<div className="edit-post-header__toolbar">
				<FullscreenModeClose />
				<NavigableToolbar
					className="edit-post-header-toolbar"
					aria-label={ __( 'Watermark tools', 'easy-watermark' ) }
				>
					<div className="edit-post-header-toolbar__left">
						<ToolbarItemComponent as={ Inserter } />
					</div>
				</NavigableToolbar>
			</div>
			<div className="edit-post-header__settings">
				<SaveButton />
				<Button
					icon="admin-settings"
					label={ __( 'Settings' ) }
					onClick={ toggleSidebar }
					isPressed={ isSidebarOpened }
					aria-expanded={ isSidebarOpened }
					shortcut={ shortcuts.toggleSidebar.display }
				/>
				<FullscreenToggle />
			</div>
		</div>
	);
};

export default compose(
	withDispatch( ( dispatch ) => {
		const {
			openSidebar,
			closeSidebar,
		} = dispatch( 'easy-watermark' );

		return {
			openSidebar,
			closeSidebar,
		};
	} ),
	withSelect( ( select ) => ( {
		isSidebarOpened: select( 'easy-watermark' ).isSidebarOpened(),
	} ) )
)( Header );
