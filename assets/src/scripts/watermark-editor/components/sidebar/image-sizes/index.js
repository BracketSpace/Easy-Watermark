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

const ImageSizes = ( { setImageSizes, imageSizes } ) => {
	const onChange = ( size, checked ) => {
		if ( checked ) {
			imageSizes[ size ].selected = true;
		} else {
			imageSizes[ size ].selected = false;
		}

		setImageSizes( imageSizes );
	};

	const items = [];

	if ( imageSizes ) {
		for ( const size in imageSizes ) {
			items.push(
				<CheckboxControl
					key={ size }
					label={ imageSizes[ size ].label }
					checked={ imageSizes[ size ].selected }
					onChange={ ( checked ) => onChange( size, checked ) }
				/>
			);
		}
	}

	return (
		<EnhancedPanelBody
			title={ __( 'Image Sizes', 'easy-watermark' ) }
			id="imageSizes"
		>
			<p>
				{ __(
					'Select image sizes which should be watermarked:',
					'easy-watermark'
				) }
			</p>
			{ items }
		</EnhancedPanelBody>
	);
};

export default compose(
	withDispatch( ( dispatch ) => ( {
		setImageSizes: dispatch( 'easy-watermark' ).setImageSizes,
	} ) ),
	withSelect( ( select ) => ( {
		imageSizes: select( 'easy-watermark' ).getImageSizes(),
	} ) )
)( ImageSizes );
