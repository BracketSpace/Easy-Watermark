/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { IconButton } from '@wordpress/components';
import { ifViewportMatches } from '@wordpress/viewport';

/**
 * Internal dependencies
 */
import shortcuts from '../../keyboard-shortcuts';

const FullscreenToggle = ( { toggleFullscreenMode, isFullscreenMode } ) => {
	const icon = isFullscreenMode ? 'editor-contract' : 'editor-expand';

	return (
		<IconButton
			aria-expanded={ isFullscreenMode }
			className="ew-fullscreen-button"
			icon={ icon }
			label={ __( 'Toggle Fullscreen' ) }
			onClick={ toggleFullscreenMode }
			shortcut={ shortcuts.toggleFullscreen.display }
		/>
	);
};

export default ifViewportMatches( 'medium' )( compose(
	withDispatch( ( dispatch, ownProps ) => ( {
		...ownProps,
		toggleFullscreenMode: () => dispatch( 'easy-watermark' ).toggleFeature( 'fullscreenMode' ),
	} ) ),
	withSelect( ( select, ownProps ) => ( {
		...ownProps,
		isFullscreenMode: select( 'easy-watermark' ).isFeatureActive( 'fullscreenMode' ),
	} ) )
)( FullscreenToggle ) );
