import $ from 'jquery'

import addNotice from '../../utils/add-notice.js'

export default class {
	constructor() {

		this.handleClick = this.handleClick.bind( this )

		this.metabox        = $( '#watermarks' )
		this.metaboxContent = this.metabox.find( '.watermarks-metabox' )
		this.errorMessage   = this.metabox.find( '.error-message' )
		this.spinners       = this.metabox.find( '.spinner' )
		this.buttons        = this.metabox.find( 'button' )
		this.form           = $( 'form#post' )
		this.postID         = this.form.find( '#post_ID' ).val()
		this.headerEnd      = $( 'hr.wp-header-end' )
		this.image          = $( '.wp_attachment_image img.thumbnail' )

		this.metabox.on( 'click', 'button', this.handleClick )

	}

	handleClick( e ) {

		e.preventDefault()

		let button = $( e.target ),
				action = button.data( 'action' )

		this.buttons.prop( 'disabled', true )
		button.next( '.spinner' ).css( 'visibility', 'visible' )

		this.errorMessage.hide();

		let data = {
			action       : 'easy-watermark/' + action,
			nonce        : button.data( 'nonce' ),
			attachment_id: this.postID
		}

		if ( 'apply_single' === action ) {
			data.watermark = button.data( 'watermark' )
		}

		$.ajax( ajaxurl, {
			data: data,
		} ).done( ( response ) => {

			if ( true === response.success ) {
				this.metaboxContent.replaceWith( response.data.metaboxContent )

				this.metaboxContent = this.metabox.find( '.watermarks-metabox' )
				this.errorMessage   = this.metabox.find( '.error-message' )
				this.spinners       = this.metabox.find( '.spinner' )
				this.buttons        = this.metabox.find( 'button' )

				if ( response.data.attachmentVersion ) {
					let imageSrc = this.image.attr( 'src' ),
							index    = imageSrc.indexOf( '?' )

					if ( -1 !== index ) {
						imageSrc = imageSrc.substr( 0, index )
					}

					imageSrc += '?v=' + response.data.attachmentVersion

					this.image.attr( 'src', imageSrc )

					if ( 'string' === typeof response.data.message ) {
						addNotice( response.data.message, 'success' )
					}
				}
			} else {
				let notice = ( 'string' === typeof response.data.message ) ? response.data.message : ew.genericErrorMessage

				addNotice( notice, 'error' )
			}

			console.log( response )

		} ).fail( ( jqXHR, textStatus ) => {

			addNotice( ew.genericErrorMessage, 'error' )
			console.log( jqXHR.status + ' ' + textStatus )

		} ).complete( () => {
			this.spinners.css( 'visibility', 'hidden' )
			this.buttons.prop( 'disabled', false )
		} )

	}
}
