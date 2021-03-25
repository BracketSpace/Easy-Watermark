/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { IconButton } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { addQueryArgs } from '@wordpress/url';

/**
 * @param {Object} props           React props.
 * @param {boolean} props.isActive Whether the fullscreen mode is active.
 * @return {React.Element} React element.
 */
function FullscreenModeClose( { isActive } ) {
	if ( ! isActive ) {
		return null;
	}

	return (
		<IconButton
			className="edit-post-fullscreen-mode-close has-icon"
			icon="arrow-left-alt2"
			href={ addQueryArgs( 'tools.php', { page: 'easy-watermark' } ) }
			label={ __( 'View Watermarks' ) }
		/>
	);
}

export default withSelect( ( select ) => ( {
	isActive: select( 'easy-watermark' ).isFeatureActive( 'fullscreenMode' ),
} ) )( FullscreenModeClose );
