import $ from 'jquery'

export default class {
	constructor() {
		this.metabox            = $( '#preview' )
		this.body               = $( 'body' )
		this.form               = $( 'form#post' )
		this.watermarkTextField = this.form.find( 'input.watermark-text' )
		this.attachmentIdField  = this.form.find( 'input.watermark-id' )
		this.link               = this.metabox.find( '.select-preview-image' )
		this.previewWrap        = this.metabox.find( '.preview-wrap' )
		this.contentWrap        = this.metabox.find( '.content-wrap' )
		this.popup              = this.metabox.find( '.ew-preview-popup' )
		this.spinner            = this.metabox.find( 'span.spinner' )
		this.image              = $( document.createElement( 'img' ) )

		this.popup.appendTo( this.body )

		this.openMediaLibrary = this.openMediaLibrary.bind( this )
		this.openPopup        = this.openPopup.bind( this )
		this.closePopup       = this.closePopup.bind( this )
		this.selectImage      = this.selectImage.bind( this )
		this.imageSelected    = this.imageSelected.bind( this )
		this.update           = this.update.bind( this )

		this.hasImage = this.previewWrap.data( 'hasImage' )

		this.link.on( 'click', this.openMediaLibrary )
		this.form.on( 'ew.update', this.update )
		this.image.on( 'click', this.openPopup )

		this.popup.find( '.media-modal-close, .media-modal-backdrop' ).on( 'click', this.closePopup )

		this.contentWrap.hide()
		this.spinner.css( 'display', 'block' )
		this.previewWrap.prepend( this.image )

		this.refreshPreview()
	}

	enable( type ) {
		this.metabox.fadeIn( 200 )
	}

	openMediaLibrary( e ) {
		e.preventDefault()

		if ( ! this.frame ) {
			this.createMediaFrame()
		}

		this.frame.open()

	}

	createMediaFrame() {

		this.frame = wp.media.frames.previewImage = wp.media( {
			title: this.link.data('choose'),
			library: {
				type: 'image'
			},
			button: {
				text: this.link.data('buttonLabel'),
				close: true
			}
		} )

		this.frame.on( 'select', this.selectImage )

	}

	selectImage() {

		let attachment  = this.frame.state().get( 'selection' ).first(),
				watermarkId = this.form.find( 'input[name=post_ID]' ).val()

		this.contentWrap.hide()
		this.spinner.css( 'display', 'block' )

		$.ajax( {
				type: "post",
				url : ajaxurl,
				data: {
					action       : 'easy-watermark/preview_image',
					attachment_id: attachment.id,
					watermark_id : watermarkId,
					nonce        : ew.previewImageNonce
				},
		} ).done( this.imageSelected ).fail( () => {
			console.log( 'fail' )
		} )

	}

	imageSelected( response ) {
		if ( true === response.success ) {
			this.link.html( this.link.data( 'changeLabel' ) )
			this.hasImage = true

			if ( response.data.popup ) {
				let popup = $( response.data.popup )

				this.popup.find( '.media-frame-content' ).replaceWith( popup.find( '.media-frame-content' ) )
			}

			this.refreshPreview()
		}
	}

	refreshPreview() {
		if ( this.hasPreview() ) {
			let time = Date.now(),
					src  = this.previewWrap.data( 'src' ) + '?t=' + time

			this.popup.find( 'img' ).each( ( i, e ) => {
				let img   = $( e ),
						src   = img.attr( 'src' ),
						index = src.indexOf( '?' )

				if ( -1 !== index ) {
					src = src.substr( 0, index )
				}

				src += '?t=' + time

				img.attr( 'src', src )
			} )

			this.image.one( 'load', () => {
				this.spinner.hide()
				this.contentWrap.fadeIn( 200 )
			} ).attr( 'src', src )
		} else {
			this.contentWrap.hide()
		}
	}

	openPopup() {
		this.popup.show()
		this.body.addClass( 'modal-open' )
	}

	closePopup() {
		this.popup.hide()
		this.body.removeClass( 'modal-open' )
	}

	hasPreview() {
		if ( ! this.hasImage ) {
			return false
		}

		let type = this.form.find( 'input.watermark-type:checked' ).val()

		if ( 'text' === type && this.watermarkTextField.val().length ) {
			return true
		}

		if ( 'image' === type && this.attachmentIdField.val().length ) {
			return true
		}

		return false
	}

	update() {
		this.refreshPreview()
	}
}
