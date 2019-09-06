/**
 * External dependencies
 */
import $ from 'jquery';
import { Model } from 'backbone';

/**
 * Internal dependencies
 */
import BulkWatermarkTool from '../views/tools/bulk-watermark';
import RestoreTool from '../views/tools/restore';
import { addNotice } from '../utils/functions.js';
import { alert } from '../includes/vex.js';
/* global ew, ajaxurl */

export default class {
	constructor() {
		this.wrap = $( '.tools' );

		if ( this.wrap.length ) {
			this.init();
		}
	}

	init() {
		this.bulkWatermark = this.bulkWatermark.bind( this );
		this.restore = this.restore.bind( this );

		this.state = new Model;
		this.state.set( {
			mode: 'none',
		} );

		this.bulkWatermarkTool = new BulkWatermarkTool( {
			state: this.state,
			callback: this.bulkWatermark,
		} );

		this.restoreTool = new RestoreTool( {
			state: this.state,
			callback: this.restore,
		} );
	}

	bulkWatermark() {
		this.state.set( {
			action: 'watermark',
			successMessage: ew.i18n.watermarkingSuccessMessage,
			statusText: ew.i18n.watermarkingStatus,
		} );

		this.getInfo();
	}

	restore() {
		this.state.set( {
			action: 'restore',
			successMessage: ew.i18n.restoringSuccessMessage,
			statusText: ew.i18n.restoringStatus,
		} );

		this.getInfo();
	}

	getInfo() {
		this.state.set( {
			mode: 'loading',
		} );

		$.ajax( ajaxurl, { data: {
			action: 'easy-watermark/tools/get-attachments',
			nonce: ew.nonce,
			mode: this.state.get( 'action' ),
		} } ).done( ( response ) => {
			if ( response.success ) {
				this.state.set( {
					items: response.data,
				} );

				this.start();
			} else {
				addNotice( ew.i18n.genericErrorMessage, 'error' );
			}
		} ).fail( () => {
			addNotice( ew.i18n.genericErrorMessage, 'error' );
		} );
	}

	start() {
		const items = this.state.get( 'items' );

		if ( ! items ) {
			alert( ew.i18n.noItemsToWatermark );
			this.state.set( { mode: 'none' } );
			return;
		}

		this.state.set( {
			mode: 'processing',
			processed: 0,
			total: items.length,
			error: false,
			backupCount: 0,
		} );

		this.doActionRecursive( items );
	}

	doActionRecursive( items ) {
		const
			attachment = items.shift(),
			nonce = this.state.get( 'nonce' ),
			watermark = this.state.get( 'watermark' );

		let
			action = 'easy-watermark/',
			processed = this.state.get( 'processed' ),
			backupCount = this.state.get( 'backupCount' );

		if ( 'watermark' === this.state.get( 'action' ) ) {
			action += ( ( 'all' === watermark ) ? 'apply_all' : 'apply_single' );
		} else {
			action += 'restore_backup';
		}

		this.state.set( {
			attachment,
		} );

		$.ajax( ajaxurl, { data: {
			action,
			nonce,
			watermark,
			attachment_id: attachment.id,
		} } ).done( ( response ) => {
			if ( true === response.success ) {
				processed++;

				if ( response.data.hasBackup ) {
					backupCount++;
				}

				this.state.set( {
					processed,
					backupCount,
				} );

				if ( items.length ) {
					this.doActionRecursive( items );
				} else {
					this.finish();
				}
			} else {
				this.fail( response.data );
			}
		} ).fail( () => {
			this.fail( ew.i18n.genericErrorMessage );
		} );
	}

	fail( errorMessage ) {
		const
			attachment = this.state.get( 'attachment' ),
			imageTitle = attachment.title,
			error = ew.i18n.bulkActionErrorMessage
				.replace( '{imageTitle}', imageTitle )
				.replace( '{error}', errorMessage );

		this.state.set( {
			error,
		} );

		this.finish();
	}

	finish() {
		const
			error = this.state.get( 'error' ),
			processed = this.state.get( 'processed' ),
			successMessage = this.state.get( 'successMessage' );

		if ( error ) {
			addNotice( error, 'error' );
		} else {
			addNotice( successMessage.replace( '{procesed}', processed ), 'success' );
		}

		this.state.set( {
			mode: 'none',
		} );
	}
}
