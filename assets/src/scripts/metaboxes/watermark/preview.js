/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { imageVersion } from '../../utils/functions';

/* global wp, ew, ajaxurl */

export default class {
	constructor() {
		this.metabox = $( '#preview' );
		this.body = $( 'body' );
		this.form = $( 'form#post' );
		this.watermarkTextField = this.form.find( 'input.watermark-text' );
		this.attachmentIdField = this.form.find( 'input.watermark-id' );
		this.link = this.metabox.find( '.select-preview-image' );
		this.previewWrap = this.metabox.find( '.preview-wrap' );
		this.contentWrap = this.metabox.find( '.content-wrap' );
		this.imageSelector = this.metabox.find( '.image-selector' );
		this.popup = this.metabox.find( '.ew-preview-popup' );
		this.spinner = this.metabox.find( 'span.spinner' );
		this.image = $( document.createElement( 'img' ) );

		this.popup.appendTo( this.body );

		this.openMediaLibrary = this.openMediaLibrary.bind( this );
		this.openPopup = this.openPopup.bind( this );
		this.closePopup = this.closePopup.bind( this );
		this.selectImage = this.selectImage.bind( this );
		this.imageSelected = this.imageSelected.bind( this );
		this.update = this.update.bind( this );

		this.hasImage = this.previewWrap.data( 'hasImage' );

		this.link.on( 'click', this.openMediaLibrary );
		this.form.on( 'ew.update', this.update );
		this.image.on( 'click', this.openPopup );

		this.popup.find( '.media-modal-close, .media-modal-backdrop' ).on( 'click', this.closePopup );

		this.imageSelector.hide();
		this.contentWrap.hide();
		this.previewWrap.prepend( this.image );

		this.refreshPreview();
	}

	enable() {
		this.metabox.fadeIn( 200 );
	}

	openMediaLibrary( e ) {
		e.preventDefault();

		if ( ! this.frame ) {
			this.createMediaFrame();
		}

		this.frame.open();
	}

	createMediaFrame() {
		this.frame = wp.media.frames.previewImage = wp.media( {
			title: this.link.data( 'choose' ),
			library: {
				type: 'image',
			},
			button: {
				text: this.link.data( 'buttonLabel' ),
				close: true,
			},
		} );

		this.frame.on( 'select', this.selectImage );
	}

	selectImage() {
		const attachment = this.frame.state().get( 'selection' ).first(),
			watermarkId = this.form.find( 'input[name=post_ID]' ).val();

		this.contentWrap.hide();
		this.spinner.css( 'display', 'block' );

		$.ajax( {
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'easy-watermark/preview_image',
				attachment_id: attachment.id,
				watermark_id: watermarkId,
				nonce: ew.previewImageNonce,
			},
		} ).done( this.imageSelected ).fail( () => {
			// TODO: handle errors.
		} );
	}

	imageSelected( response ) {
		if ( true === response.success ) {
			this.link.html( this.link.data( 'changeLabel' ) );
			this.hasImage = true;

			if ( response.data.popup ) {
				const popup = $( response.data.popup );

				this.popup.find( '.media-frame-content' ).replaceWith( popup.find( '.media-frame-content' ) );
			}

			this.refreshPreview();
		}
	}

	refreshPreview() {
		this.contentWrap.hide();

		if ( this.hasImage ) {
			this.imageSelector.hide();
			this.spinner.css( 'display', 'block' );

			const src = imageVersion( this.previewWrap.data( 'src' ) );

			this.popup.find( 'img' ).each( ( i, e ) => {
				const
					img = $( e ),
					psrc = imageVersion( img.attr( 'src' ) );

				img.attr( 'src', psrc );
			} );

			this.image.one( 'load', () => {
				this.spinner.hide();
				this.contentWrap.fadeIn( 200 );
				this.imageSelector.fadeIn( 200 );
			} ).attr( 'src', src );
		} else {
			this.imageSelector.show();
		}
	}

	openPopup() {
		this.popup.show();
		this.body.addClass( 'modal-open' );
	}

	closePopup() {
		this.popup.hide();
		this.body.removeClass( 'modal-open' );
	}

	hasPreview() {
		const type = this.form.find( 'input.watermark-type:checked' ).val();

		if ( 'text' === type && this.watermarkTextField.val().length ) {
			return true;
		}

		if ( 'image' === type && this.attachmentIdField.val().length ) {
			return true;
		}

		return false;
	}

	update() {
		this.refreshPreview();
	}
}
