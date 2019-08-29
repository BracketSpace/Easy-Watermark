/**
 * External dependencies
 */
import $ from 'jquery';
import { Model } from 'backbone';

/**
 * Internal dependencies
 */
import WatermarkSelector from '../views/media-list/watermark-selector';
import Status from '../views/media-list/status';
/**
 * Internal dependencies
 */
import { addNotice, imageVersion } from '../utils/functions.js';

/* global ew, ajaxurl */

class BulkActions extends Model {
	constructor() {
		super();

		this.bulkActionsSelectors = $( 'select#bulk-action-selector-top, select#bulk-action-selector-bottom' );

		if ( this.bulkActionsSelectors.length ) {
			this.init();
		}
	}

	init() {
		this.selectBulkAction = this.selectBulkAction.bind( this );
		this.doAction = this.doAction.bind( this );

		this.form = $( 'form#posts-filter' );
		this.actionButtons = this.form.find( '#doaction, #doaction2' );

		this.bulkActionsSelectors.each( ( n, item ) => {
			const select = $( item );

			new WatermarkSelector( {
				controller: this,
				bulkActionSelector: select,
			} ).render();

			new Status( {
				controller: this,
				bulkActionSelector: select,
			} ).render();
		} ).val( -1 ).on( 'change', this.selectBulkAction );

		this.actionButtons.on( 'click', this.doAction );
	}

	selectBulkAction( e ) {
		const select = $( e.target );

		this.set( {
			select,
			action: select.val(),
		} );
	}

	doAction( e ) {
		const action = this.get( 'action' );

		if ( ! this.checkAction( action ) ) {
			return;
		}

		e.preventDefault();

		const watermark = this.get( 'watermark' );

		if ( 'watermark' === action && ! watermark ) {
			return;
		}

		const
			statusText = 'watermark' === action ? ew.i18n.watermarkingStatus : ew.i18n.restoringStatus,
			successMessage = 'watermark' === action ? ew.i18n.watermarkingSuccessMessage : ew.i18n.restoringSuccessMessage;

		this.set( {
			statusText,
			successMessage,
		} );

		const attachments = [];

		this.form.find( 'input[name="media[]"]:checked' ).each( ( n, checkbox ) => {
			attachments.push( $( checkbox ).val() );
		} );

		if ( ! attachments.length ) {
			return;
		}

		this.status().set( {
			processing: true,
			total: attachments.length,
			processed: 0,
			success: 0,
		} );

		this.set( 'attachments', attachments );

		this.doActionRecursive();
	}

	doActionRecursive() {
		const
			bulkAction = this.get( 'action' ),
			watermark = this.get( 'watermark' );

		let
			action = 'easy-watermark/',
			nonce;

		if ( 'watermark' === bulkAction ) {
			action += ( ( 'all' === watermark ) ? 'apply_all' : 'apply_single' );
			nonce = ( 'all' === watermark ) ? ew.applyAllNonce : ew.applySingleNonces[ watermark ];
		} else if ( 'restore' === bulkAction ) {
			action += 'restore_backup';
			nonce = ew.restoreBackupNonce;
		} else {
			return;
		}

		const
			status = this.status(),
			attachments = this.get( 'attachments' ),
			data = { action, nonce, watermark };

		let
			processed = status.get( 'processed' ),
			success = status.get( 'success' );

		data.attachment_id = attachments.shift();

		this.set( 'currentAttachmentID', data.attachment_id );

		$.ajax( ajaxurl, {
			data,
		} ).done( ( response ) => {
			if ( true === response.success ) {
				if ( response.data.attachmentVersion ) {
					const img = this.form.find( 'tr#post-' + data.attachment_id + ' img' ),
						src = imageVersion( img.attr( 'src' ), response.data.attachmentVersion );

					img.attr( { src, srcset: '' } );
				}

				if ( true === response.data.result ) {
					success++;
				}

				processed++;
				status.set( { processed, success } );

				if ( attachments.length ) {
					this.doActionRecursive();
				} else {
					this.actionDone();
				}
			} else {
				const error = ( 'string' === typeof response.data.message ) ? response.data.message : ew.i18n.genericErrorMessage;
				this.actionError( error );
			}
		} ).fail( () => {
			this.actionError( ew.i18n.genericErrorMessage );
		} );
	}

	actionError( error ) {
		this.status().set( { error } );
		this.actionDone();
	}

	actionDone() {
		const
			status = this.status(),
			success = status.get( 'success' ),
			error = status.get( 'error' ),
			successMessage = this.get( 'successMessage' ),
			currentID = this.get( 'currentAttachmentID' );

		if ( success > 0 ) {
			addNotice( successMessage.replace( '{procesed}', success ), 'success' );
		}

		if ( error ) {
			const
				row = this.form.find( 'tr#post-' + currentID ),
				imageTitle = row.find( '.column-title a' ).attr( 'aria-label' );

			const errorMessage = ew.i18n.bulkActionErrorMessage
				.replace( '{imageTitle}', imageTitle )
				.replace( '{error}', error );
			addNotice( errorMessage, 'error' );
		}

		this.status().set( 'processing', false );
	}

	status() {
		let status = this.get( 'status' );

		if ( undefined === status ) {
			status = new Model;
			status.set( {
				processed: 0,
				total: 0,
			} );

			this.set( { status } );
		}

		return status;
	}

	checkAction( action ) {
		return [ 'watermark', 'restore' ].includes( action );
	}
}

$( document ).ready( () => new BulkActions );
