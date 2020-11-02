/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { CheckboxControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import EnhancedPanelBody from '../enhanced-panel-body';

const ImageTypes = ( {
	isAutoWatermarkEnabled,
	setImageTypes,
	imageTypes,
} ) => {
	if ( ! isAutoWatermarkEnabled ) {
		return null;
	}

	const onChange = ( type, checked ) => {
		if ( checked ) {
			imageTypes[ type ].selected = true;
		} else {
			imageTypes[ type ].selected = false;
		}

		setImageTypes( imageTypes );
	};

	const items = [];

	if ( imageTypes ) {
		for ( const type in imageTypes ) {
			items.push(
				<CheckboxControl
					key={ type }
					label={ imageTypes[ type ].label }
					checked={ imageTypes[ type ].selected }
					onChange={ ( checked ) => onChange( type, checked ) }
				/>
			);
		}
	}

	return (
		<EnhancedPanelBody
			title={ __( 'Image Types', 'easy-watermark' ) }
			id="imageTypes" >
			<p>{ __( 'Select image types which should be watermarked on upload:', 'easy-watermark' ) }</p>
			{ items }
		</EnhancedPanelBody>
	);
};

export default compose(
	withDispatch( ( dispatch ) => ( {
		setImageTypes: dispatch( 'easy-watermark' ).setImageTypes,
	} ) ),
	withSelect( ( select ) => {
		const {
			getConfig,
			getImageTypes,
		} = select( 'easy-watermark' );

		return {
			isAutoWatermarkEnabled: getConfig( 'auto_add' ),
			imageTypes: getImageTypes(),
		};
	} ),
)( ImageTypes );
