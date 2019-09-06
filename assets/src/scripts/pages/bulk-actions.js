/**
 * External dependencies
 */
import $ from 'jquery';
import { Model, Collection } from 'backbone';

/**
 * Internal dependencies
 */
import WatermarkSelector from '../views/media-list/watermark-selector';
import Status from '../views/media-list/status';
import Attachment from '../views/media-list/attachment';

import {
	addNotice,
	imageVersion,
	filterSelection,
} from '../utils/functions.js';

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

		this.set( {
			attachments: new Collection,
			selection: new Collection,
		} );

		this.actionButtons.on( 'click', this.doAction );

		this.on( 'bulkAction:start', () => {
			this.bulkActionsSelectors.val( -1 ).prop( 'disabled', true );
			this.actionButtons.prop( 'disabled', true );
		} );

		this.on( 'bulkAction:finished', () => {
			this.bulkActionsSelectors.prop( 'disabled', false );
			this.actionButtons.prop( 'disabled', false );
		} );
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

		const checkedItems = this.form.find( 'input[name="media[]"]:checked' );

		if ( ! checkedItems.length ) {
			this.status().set( { text: ew.i18n.noItemsSelected } );
			return;
		}

		const
			attachments = this.get( 'attachments' ),
			selection = this.get( 'selection' ),
			attachmentIds = [];

		this.trigger( 'bulkAction:start' );

		checkedItems.each( ( n, checkbox ) => {
			const
				id = $( checkbox ).val(),
				model = attachments.get( id );

			if ( model ) {
				selection.add( model );
			} else {
				attachmentIds.push( id );
			}
		} );

		if ( ! attachmentIds.length ) {
			if ( selection.length ) {
				this.prepare();
			}

			return;
		}

		this.status().set( {
			text: '<span class="spinner ew-spinner"></span>',
		} );

		$.ajax( ajaxurl, { data: {
			action: 'easy-watermark/attachments-info',
			nonce: ew.attachmentsInfoNonce,
			attachments: attachmentIds,
		} } ).done( ( response ) => {
			if ( true === response.success ) {
				for ( const item of response.data ) {
					const model = new Model( item );

					attachments.push( model );
					selection.push( model );

					new Attachment( {
						el: `#post-${ item.id }`,
						controller: this,
						model,
					} );
				}

				this.prepare();
			} else {
				const error = response.data.message ? response.data.message : ew.i18n.genericErrorMessage;
				this.actionError( error );
			}
		} ).fail( () => {
			this.actionError( ew.i18n.genericErrorMessage );
		} );
	}

	prepare() {
		const
			action = this.get( 'action' ),
			selection = this.get( 'selection' ),
			backup = ( 'restore' === action ),
			statusText = 'watermark' === action ? ew.i18n.watermarkingStatus : ew.i18n.restoringStatus,
			successMessage = 'watermark' === action ? ew.i18n.watermarkingSuccessMessage : ew.i18n.restoringSuccessMessage;

		filterSelection( selection, backup );

		if ( ! selection.length ) {
			this.status().set( {
				successMessage: 'watermark' === action ? ew.i18n.watermarkingNoItems : ew.i18n.restoringNoItems,
			} );

			this.actionDone();
			return;
		}

		this.status().set( {
			successMessage,
			text: statusText,
			processing: true,
			total: selection.length,
			processed: 0,
		} );

		for ( const model of selection.models ) {
			model.trigger( 'processing:start' );
		}

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
			selection = this.get( 'selection' ),
			model = selection.shift(),
			data = { action, nonce, watermark };

		let processed = status.get( 'processed' );

		data.attachment_id = model.get( 'id' );

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

				processed++;
				status.set( { processed } );

				model.set( 'hasBackup', response.data.hasBackup ? true : false );
				model.trigger( 'processing:done' );

				if ( selection.length ) {
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
		this.trigger( 'bulkAction:finished' );

		this.bulkActionsSelectors.prop( 'disabled', false );

		const
			status = this.status(),
			processed = status.get( 'processed' ),
			error = status.get( 'error' ),
			successMessage = status.get( 'successMessage' ),
			currentID = this.get( 'currentAttachmentID' );

		if ( processed > 0 ) {
			addNotice( successMessage.replace( '{procesed}', processed ), 'success' );
		} else {
			addNotice( successMessage, 'info' );
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

		this.status().set( {
			processing: false,
			processed: 0,
			total: 0,
			text: '',
		} );
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
