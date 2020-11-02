/**
 * WordPress dependencies
 */
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { Button, IconButton } from '@wordpress/components';
import { withDispatch, withSelect } from '@wordpress/data';

const SidebarHeader = ( { name, closeSidebar, toggleSidebarTab, activeTab } ) => {
	const closeLabel = __( 'Close Settings' );
	const objectLabel = __( 'Object' );

	const [ generalAriaLabel, generalActiveClass ] = activeTab === 'general' ?
		// translators: ARIA label for the General sidebar tab, selected.
		[ __( 'General (selected)' ), 'is-active' ] :
		// translators: ARIA label for the General sidebar tab, not selected.
		[ __( 'General' ), '' ];

	const [ objectAriaLabel, objectActiveClass ] = activeTab === 'object' ?
		// translators: ARIA label for the Object sidebar tab, selected.
		[ __( 'Object (selected)' ), 'is-active' ] :
		// translators: ARIA label for the Object sidebar tab, not selected.
		[ __( 'Object' ), '' ];

	return (
		<>
			<div className="components-panel__header edit-post-sidebar-header__small">
				<span className="edit-post-sidebar-header__title">
					{ name || __( '(no name)' ) }
				</span>
				<IconButton
					onClick={ closeSidebar }
					icon="no-alt"
					label={ closeLabel }
				/>
			</div>
			<div className="components-panel__header edit-post-sidebar-header edit-post-sidebar__panel-tabs">
				<ul>
					<li>
						<Button
							onClick={ () => toggleSidebarTab( 'general' ) }
							className={ `edit-post-sidebar__panel-tab ${ generalActiveClass }` }
							aria-label={ generalAriaLabel }
							data-label={ __( 'General' ) }
						>
							{ __( 'General' ) }
						</Button>
					</li>
					<li>
						<Button
							onClick={ () => toggleSidebarTab( 'object' ) }
							className={ `edit-post-sidebar__panel-tab ${ objectActiveClass }` }
							aria-label={ objectAriaLabel }
							data-label={ objectLabel }
						>
							{ objectLabel }
						</Button>
					</li>
				</ul>
			</div>
		</>
	);
};

export default compose(
	withSelect( ( select ) => {
		const { getAttribute, getActiveSidebarTab, isSidebarOpened } = select( 'easy-watermark' );

		return {
			name: getAttribute( 'title' ),
			activeTab: getActiveSidebarTab(),
			isSidebarOpened: isSidebarOpened(),
		};
	} ),
	withDispatch( ( dispatch ) => {
		const { closeSidebar, toggleSidebarTab } = dispatch( 'easy-watermark' );

		return {
			closeSidebar,
			toggleSidebarTab,
		};
	} ),
)( SidebarHeader );
