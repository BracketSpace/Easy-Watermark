/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { TextControl, PanelBody } from '@wordpress/components';

const WatermarkName = ( { editAttribute, watermarkName } ) => {
	return (
		<PanelBody>
			<TextControl
				className="watermark-name-control"
				label={ __( 'Watermark Name' ) }
				value={ watermarkName }
				placeholder={ __( 'Name your watermark...', 'easy-watermark' ) }
				onChange={ ( value ) => {
					editAttribute( 'title', value );
				} }
			/>
		</PanelBody>
	);
};

export default compose(
	withDispatch( ( dispatch ) => ( {
		editAttribute: dispatch( 'easy-watermark' ).editAttribute,
	} ) ),
	withSelect( ( select ) => ( {
		watermarkName: select( 'easy-watermark' ).getAttribute( 'title' ),
	} ) ),
)( WatermarkName );
