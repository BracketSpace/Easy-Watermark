/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { withDispatch, withSelect } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { KeyboardShortcuts } from '@wordpress/components';

/**
 * External dependencies
 */
import { boundMethod } from 'autobind-decorator';

/**
 * Internal dependencies
 */
import shortcuts from '../../keyboard-shortcuts';

class EditorShortcuts extends Component {
	@boundMethod
	toggleSidebar() {
		const { isSidebarOpened, openSidebar, closeSidebar } = this.props;

		if ( isSidebarOpened ) {
			closeSidebar();
		} else {
			openSidebar();
		}
	}

	render() {
		const { toggleFullscreenMode } = this.props;

		return (
			<KeyboardShortcuts
				bindGlobal
				shortcuts={ {
					[ shortcuts.toggleSidebar.raw ]: this.toggleSidebar,
					[ shortcuts.toggleFullscreen.raw ]: toggleFullscreenMode,
				} }
			/>
		);
	}
}

export default compose(
	withDispatch( ( dispatch ) => {
		const { toggleFeature, openSidebar, closeSidebar } = dispatch(
			'easy-watermark'
		);

		return {
			toggleFullscreenMode: () => toggleFeature( 'fullscreenMode' ),
			openSidebar,
			closeSidebar,
		};
	} ),
	withSelect( ( select ) => {
		return {
			isSidebarOpened: select( 'easy-watermark' ).isSidebarOpened(),
		};
	} )
)( EditorShortcuts );
