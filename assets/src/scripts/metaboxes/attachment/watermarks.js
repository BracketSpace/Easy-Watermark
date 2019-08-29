/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { addNotice, imageVersion } from '../../utils/functions.js';

/* global ew, ajaxurl */

export default class {
	constructor() {
		this.handleClick = this.handleClick.bind( this );

		this.metabox = $( '#watermarks' );
		this.metaboxContent = this.metabox.find( '.watermarks-metabox' );
		this.errorMessage = this.metabox.find( '.error-message' );
		this.spinners = this.metabox.find( '.spinner' );
		this.buttons = this.metabox.find( 'button' );
		this.form = $( 'form#post' );
		this.postID = this.form.find( '#post_ID' ).val();
		this.headerEnd = $( 'hr.wp-header-end' );
		this.image = $( '.wp_attachment_image img.thumbnail' );

		this.metabox.on( 'click', 'button', this.handleClick );
	}

	handleClick( e ) {
		e.preventDefault();

		const button = $( e.target ),
			action = button.data( 'action' );

		this.buttons.prop( 'disabled', true );
		button.next( '.spinner' ).css( 'visibility', 'visible' );

		this.errorMessage.hide();

		const data = {
			action: 'easy-watermark/' + action,
			nonce: button.data( 'nonce' ),
			attachment_id: this.postID,
		};

		if ( 'apply_single' === action ) {
			data.watermark = button.data( 'watermark' );
		}

		$.ajax( ajaxurl, {
			data,
		} ).done( ( response ) => {
			if ( true === response.success ) {
				this.metaboxContent.replaceWith( response.data.metaboxContent );

				this.metaboxContent = this.metabox.find( '.watermarks-metabox' );
				this.errorMessage = this.metabox.find( '.error-message' );
				this.spinners = this.metabox.find( '.spinner' );
				this.buttons = this.metabox.find( 'button' );

				if ( response.data.attachmentVersion ) {
					const src = imageVersion( this.image.attr( 'src' ), response.data.attachmentVersion );

					this.image.attr( 'src', src );

					if ( 'string' === typeof response.data.message ) {
						addNotice( response.data.message, 'success' );
					}
				}
			} else {
				const notice = ( 'string' === typeof response.data.message ) ? response.data.message : ew.genericErrorMessage;

				addNotice( notice, 'error' );
			}
		} ).fail( () => {
			addNotice( ew.genericErrorMessage, 'error' );
		} ).complete( () => {
			this.spinners.css( 'visibility', 'hidden' );
			this.buttons.prop( 'disabled', false );
		} );
	}
}
