/**
 * WordPress dependencies
 */
import { EditorNotices } from '@wordpress/editor';
import { FocusReturnProvider } from '@wordpress/components';
import { withSelect } from '@wordpress/data';
import { InterfaceSkeleton } from '@wordpress/interface';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Internal dependencies
 */
import EditorContent from '../editor-content';
import FullscreenMode from '../fullscreen-mode';
import Header from '../header';
import KeyboardShortcuts from '../keyboard-shortcuts';
import Sidebar from '../sidebar';

const Layout = ( { isSidebarOpened } ) => {
	const className = classnames( 'edit-post-layout', {
		'is-sidebar-opened': isSidebarOpened,
	} );

	return (
		<FocusReturnProvider className={ className }>
			<KeyboardShortcuts />
			<FullscreenMode />
			<InterfaceSkeleton
				className={ className }
				header={ <Header /> }
				sidebar={ <Sidebar /> }
				content={ (
					<>
						<EditorNotices />
						<EditorContent />
					</>
				) }
			/>
		</FocusReturnProvider>
	);
};

export default withSelect( ( select ) => {
	return {
		isSidebarOpened: select( 'easy-watermark' ).isSidebarOpened(),
	};
} )( Layout );
