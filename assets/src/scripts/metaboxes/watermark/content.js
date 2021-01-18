/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { imageVersion } from '../../utils/functions';

/* global wp */

export default class {
	constructor() {
		this.metabox = $( '#watermark-content' );
		this.form = $( 'form#post' );

		this.imageContent = this.metabox.find( '.image-content' );
		this.textContent = this.metabox.find( '.text-content' );

		this.buttonWrap = this.metabox.find( '.select-image-button' );
		this.button = this.buttonWrap.find( 'a' );

		this.imageWrap = this.metabox.find( '.watermark-image' );
		this.image = this.imageWrap.find( 'img' );
		this.mimeTypeField = this.metabox.find( 'input.watermark-mime-type' );
		this.urlField = this.metabox.find( 'input.watermark-url' );
		this.attachmentIdField = this.metabox.find( 'input.watermark-id' );
		this.opacityField = this.metabox.find( 'input#opacity' );
		this.opacityFieldDesc = this.metabox.find( '.opacity-desc' );
		this.watermarkTextField = this.metabox.find( 'input.watermark-text' );

		this.attachmentId = this.attachmentIdField.val();

		this.openMediaLibrary = this.openMediaLibrary.bind( this );
		this.update = this.update.bind( this );
		this.watermarkTextChange = this.watermarkTextChange.bind( this );

		this.form.on( 'ew.update', this.update );
		this.button.on( 'click', this.openMediaLibrary );
		this.image.on( 'click', this.openMediaLibrary );

		if ( this.image.attr( 'src' ) ) {
			this.imageWrap.show();
			this.switchOpacityField( this.mimeTypeField.val() );
		} else {
			this.buttonWrap.show();
		}

		this.textChangeTimeout = null;

		this.watermarkTextField.on( 'input', this.watermarkTextChange );
	}

	enable( type ) {
		this.metabox.fadeIn( 200 );

		if ( type === 'image' ) {
			this.imageContent.show();
			this.textContent.hide();
			this.opacityField.prop( 'disabled', false );
		} else {
			this.textContent.show();
			this.imageContent.hide();
			this.opacityField.prop( 'disabled', true );
			this.prepareTextPreview();
		}
	}

	watermarkTextChange() {
		clearTimeout( this.textChangeTimeout );

		this.textChangeTimeout = setTimeout( () => {
			this.form.trigger( 'ew.save' );
		}, 500 );
	}

	prepareTextPreview() {
		if ( ! this.previewWrap ) {
			this.previewWrap = this.metabox.find( '.text-preview' );
			this.preview = $( document.createElement( 'img' ) );

			this.previewWrap.hide().append( this.preview );
		}

		this.refreshPreview();
	}

	refreshPreview() {
		if ( this.watermarkTextField.val().length ) {
			const src = imageVersion( this.previewWrap.data( 'src' ) );

			this.preview.attr( 'src', src );
			this.previewWrap.show();
		} else {
			this.previewWrap.hide();
		}
	}

	openMediaLibrary( e ) {
		e.preventDefault();

		if ( ! this.frame ) {
			this.createMediaFrame();
		}

		this.frame.open();
	}

	createMediaFrame() {
		this.frame = wp.media.frames.watermarkSelection = wp.media( {
			title: this.button.data( 'choose' ),
			library: {
				type: 'image',
			},
			button: {
				text: this.button.data( 'buttonLabel' ),
				close: true,
			},
		} );

		this.frame
			.on( 'select', this.selectImage, this )
			.on( 'open', this.applySelection, this )
			.on( 'close', this.checkSelectedAttachment, this );
	}

	selectImage() {
		const
			attachment = this.frame.state().get( 'selection' ).first(),
			mime = attachment.get( 'mime' ),
			url = attachment.get( 'url' );

		this.attachmentId = attachment.get( 'id' );

		this.mimeTypeField.val( mime );
		this.urlField.val( url );
		this.attachmentIdField.val( this.attachmentId );

		this.switchOpacityField( mime );

		this.image.attr( 'src', url );
		this.imageWrap.show();
		this.buttonWrap.hide();

		this.form.trigger( 'ew.save' );
	}

	applySelection() {
		if ( this.attachmentId ) {
			const
				selection = this.frame.state().get( 'selection' ),
				attachment = wp.media.attachment( this.attachmentId );

			attachment.fetch();

			if ( attachment ) {
				selection.add( [ attachment ] );
			}
		}
	}

	checkSelectedAttachment() {
		const attachment = wp.media.attachment( this.attachmentId );

		attachment.fetch();

		if ( ! attachment || true === attachment.destroyed ) {
			this.mimeTypeField.val( '' );
			this.urlField.val( '' );
			this.attachmentIdField.val( '' );

			this.imageWrap.hide();
			this.buttonWrap.show();
		}
	}

	switchOpacityField( imgType ) {
		if ( 'image/png' === imgType ) {
			this.opacityField.parent().hide();
			this.opacityFieldDesc.show();
		} else {
			this.opacityField.parent().show();
			this.opacityFieldDesc.hide();
		}
	}

	update() {
		if ( 'text' === this.form.find( 'input.watermark-type:checked' ).val() ) {
			this.refreshPreview();
		}
	}
}
