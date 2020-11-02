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

const PostTypes = ( {
	isAutoWatermarkEnabled,
	setPostTypes,
	postTypes,
} ) => {
	if ( ! isAutoWatermarkEnabled ) {
		return null;
	}

	const onChange = ( type, checked ) => {
		if ( checked ) {
			postTypes[ type ].selected = true;
		} else {
			postTypes[ type ].selected = false;
		}

		setPostTypes( postTypes );
	};

	const items = [];

	if ( postTypes ) {
		for ( const type in postTypes ) {
			items.push(
				<CheckboxControl
					key={ type }
					label={ postTypes[ type ].label }
					checked={ postTypes[ type ].selected }
					onChange={ ( checked ) => onChange( type, checked ) }
				/>
			);
		}
	}

	return (
		<EnhancedPanelBody
			title={ __( 'Post Types', 'easy-watermark' ) }
			id="postTypes" >
			<p>{ __( 'Select which post type attachments should be watermarked on upload:', 'easy-watermark' ) }</p>
			{ items }
		</EnhancedPanelBody>
	);
};

export default compose(
	withDispatch( ( dispatch ) => ( {
		setPostTypes: dispatch( 'easy-watermark' ).setPostTypes,
	} ) ),
	withSelect( ( select ) => {
		const {
			getConfig,
			getPostTypes,
		} = select( 'easy-watermark' );

		return {
			isAutoWatermarkEnabled: getConfig( 'auto_add' ),
			postTypes: getPostTypes(),
		};
	} ),
)( PostTypes );
