/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component, forwardRef } from '@wordpress/element';
import { Dropdown, Button, MenuItem } from '@wordpress/components';
import { withDispatch } from '@wordpress/data';

/**
 * External dependencies
 */
import { boundMethod } from 'autobind-decorator';

/**
 * Internal dependencies
 */
import { plus, text } from 'icons';

const { wp } = window;

class Inserter extends Component {
	mediaFrame = false;

	@boundMethod
	onToggle( isOpen ) {
		const { onToggle } = this.props;

		// Surface toggle callback to parent component
		if ( onToggle ) {
			onToggle( isOpen );
		}
	}

	@boundMethod
	createTextObject() {
		this.props.createObject( {
			type: 'text',
		} );
	}

	@boundMethod
	createImageObject() {
		const attachment = this.mediaFrame.state().get( 'selection' ).first();

		this.props.createObject( {
			type: 'image',
			attachment_id: attachment.get( 'id' ),
			url: attachment.get( 'url' ),
			mime_type: `${ attachment.get( 'type' ) }/${ attachment.get(
				'subtype'
			) }`,
		} );
	}

	getMediaFrame() {
		if ( ! this.mediaFrame ) {
			this.mediaFrame = wp.media( {
				library: {
					type: [ 'image' ],
				},
			} );

			this.mediaFrame.on( 'select', this.createImageObject );
		}

		return this.mediaFrame;
	}

	@boundMethod
	openMediaFrame() {
		this.getMediaFrame().open();
	}

	@boundMethod
	renderContent( { onClose } ) {
		return (
			<>
				<MenuItem
					icon="format-image"
					onClick={ () => {
						onClose();
						this.openMediaFrame();
					} }
				>
					{ __( 'Image', 'easy-watermark' ) }
				</MenuItem>
				<MenuItem
					icon={ text }
					onClick={ () => {
						onClose();
						this.createTextObject();
					} }
				>
					{ __( 'Text', 'easy-watermark' ) }
				</MenuItem>
			</>
		);
	}

	@boundMethod
	renderToggle( { onToggle, isOpen } ) {
		const { disabled, innerRef } = this.props;

		return (
			<Button
				icon={ plus }
				label={ __( 'Add object' ) }
				tooltipPosition="bottom"
				showTooltip={ true }
				onClick={ onToggle }
				className="edit-post-header-toolbar__inserter-toggle"
				aria-haspopup="true"
				aria-expanded={ isOpen }
				disabled={ disabled }
				ref={ innerRef }
				isPrimary
			/>
		);
	}

	render() {
		return (
			<Dropdown
				className="editor-inserter watermark-editor-inserter"
				contentClassName="editor-inserter__popover watermark-editor-inserter__popover"
				position={ 'top right' }
				onToggle={ this.onToggle }
				headerTitle={ __( 'Add a block' ) }
				renderToggle={ this.renderToggle }
				renderContent={ this.renderContent }
			/>
		);
	}
}

const InserterWithDispatch = withDispatch( ( dispatch ) => ( {
	createObject: dispatch( 'easy-watermark' ).createObject,
} ) )( Inserter );

export default forwardRef( ( props, ref ) => {
	return <InserterWithDispatch innerRef={ ref } { ...props } />;
} );
