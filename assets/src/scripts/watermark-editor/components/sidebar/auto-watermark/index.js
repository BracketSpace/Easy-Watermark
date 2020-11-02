/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import EnhancedPanelBody from '../enhanced-panel-body';

const AutoWatermark = ( {
	editConfig,
	isAutoWatermarkEnabled,
	allowForAll,
} ) => {
	const allowForAllToggleLabel = sprintf(
		__( 'With this option enabled auto watermarking will work for each user regardless of role-based <a href="%s">permission settings</a>.', 'easy-watermark' ),
		ew.permissionSettingsURL
	);

	return (
		<EnhancedPanelBody
			title={ __( 'Auto Watermark', 'easy-watermark' ) }
			id="autoWatermark" >
			<ToggleControl
				label={ __( 'Enable Auto Watermark', 'easy-watermark' ) }
				help={ __( 'This option will automatically add watermak on image upload.', 'easy-watermark' ) }
				checked={ isAutoWatermarkEnabled }
				onChange={ ( checked ) => editConfig( 'auto_add', checked ) }
			/>
			{ isAutoWatermarkEnabled && (
				<ToggleControl
					label={ __( 'Alow For All', 'easy-watermark' ) }
					help={ (
						<span dangerouslySetInnerHTML={ { __html: allowForAllToggleLabel } } />
					) }
					checked={ allowForAll }
					onChange={ ( checked ) => editConfig( 'auto_add_all', checked ) }
				/>
			) }
		</EnhancedPanelBody>
	);
};

export default compose(
	withDispatch( ( dispatch ) => ( {
		editConfig: dispatch( 'easy-watermark' ).editConfig,
	} ) ),
	withSelect( ( select ) => {
		const { getConfig } = select( 'easy-watermark' );

		return {
			isAutoWatermarkEnabled: getConfig( 'auto_add' ),
			allowForAll: getConfig( 'auto_add_all' ),
		};
	} ),
)( AutoWatermark );
