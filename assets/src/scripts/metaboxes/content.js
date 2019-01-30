import $ from 'jquery'

export default class {
	constructor() {
		this.metabox = $( '#watermark-content' )

		this.imageContent = this.metabox.find( '.image-content' )
		this.textContent  = this.metabox.find( '.text-content' )

		this.buttonWrap = this.metabox.find( '.select-image-button' )
		this.button     = this.buttonWrap.find( 'a' )

		this.imageWrap         = this.metabox.find( '.watermark-image' )
		this.image             = this.imageWrap.find( 'img' )
		this.mimeTypeField     = this.metabox.find( 'input.watermark-mime-type' )
		this.urlField          = this.metabox.find( 'input.watermark-url' )
		this.attachmentIdField = this.metabox.find( 'input.watermark-id' )

		this.openMediaLibrary = this.openMediaLibrary.bind( this )
		this.selectImage      = this.selectImage.bind( this )

		this.button.on( 'click', this.openMediaLibrary )
		this.image.on( 'click', this.openMediaLibrary )

		if ( this.image.attr( 'src' ) ) {
			this.imageWrap.show();
		} else {
			this.buttonWrap.show();
		}
	}

	enable( type ) {
		this.metabox.fadeIn( 200 )

		if ( type == 'image' ) {
			this.imageContent.show()
			this.textContent.hide()
		} else {
			this.textContent.show()
			this.imageContent.hide()
		}
	}

	openMediaLibrary( e ) {
		e.preventDefault()

		if ( ! this.frame ) {
			this.createMediaFrame()
		}

		this.frame.open()

	}

	createMediaFrame() {

		this.frame = wp.media.frames.customHeader = wp.media( {
			title: this.button.data('choose'),
			library: {
				type: 'image'
			},
			button: {
				text: this.button.data('buttonLabel'),
				close: true
			}
		} )

		this.frame.on( 'select', this.selectImage )

	}

	selectImage() {

		let attachment = this.frame.state().get( 'selection' ).first()

		console.log( attachment )

		// $('#easy-watermark-url').val(attachment.attributes.url)
		this.mimeTypeField.val( attachment.attributes.mime )
		this.urlField.val( attachment.attributes.url )
		this.attachmentIdField.val( attachment.id )

		this.image.attr( 'src', attachment.attributes.url )
		this.imageWrap.show()
		this.buttonWrap.hide();

	}
}
