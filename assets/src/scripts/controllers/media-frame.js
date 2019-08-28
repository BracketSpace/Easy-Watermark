/**
 * External dependencies
 */
import $ from 'jquery';
import { Collection, Model } from 'backbone';

/**
 * Internal dependencies
 */
import {
	addNotice,
	imageVersion,
	filterSelection,
} from '../utils/functions.js';

/* global wp, ew, ajaxurl */

if ( wp.media && wp.media.view && wp.media.view.MediaFrame && 'function' === typeof wp.media.view.MediaFrame.Manage ) {
	wp.media.view.MediaFrame.Manage = class extends wp.media.view.MediaFrame.Manage {
		initialize() {
			super.initialize();

			this.on( 'watermarking:activate', this.ewWatermark, this );
			this.on( 'restoring:activate', this.ewRestoreBackup, this );
			this.on( 'watermarking:deactivate restoring:deactivate', this.ewCancelBulkAction, this );
		}

		browseContent( contentRegion ) {
			this.state().set( {
				ewCollection: new Collection,
				ewStatus: new Model,
			} );

			super.browseContent( contentRegion );
		}

		ewBulkAction() {
			const
				state = this.state(),
				collection = state.get( 'ewCollection' ),
				status = state.get( 'ewStatus' ),
				selection = state.get( 'selection' );

			filterSelection( selection );

			collection.reset();

			for ( const model of selection.models ) { // eslint-disable-line no-unused-vars
				collection.add( model );
				model.trigger( 'ewBulkAction:start' );
			}

			this.deactivateMode( 'watermark' ).deactivateMode( 'select' ).trigger( 'selection:action:done' );

			status.set( {
				total: collection.length,
				processed: 0,
				error: false,
			} );

			this.ewBulkActionRecursive();
		}

		ewBulkActionRecursive() {
			const
				state = this.state(),
				bulkAction = state.get( 'ewAction' ),
				watermark = state.get( 'watermark' );

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
				status = state.get( 'ewStatus' ),
				collection = state.get( 'ewCollection' ),
				model = collection.shift(),
				data = { action, nonce, watermark };

			let processed = status.get( 'processed' );

			data.attachment_id = model.get( 'id' );

			state.set( 'ewCurrentModel', model );

			$.ajax( ajaxurl, {
				data,
			} ).done( ( response ) => {
				if ( true === response.success ) {
					if ( response.data.attachmentVersion ) {
						model.set( 'url', imageVersion( model.get( 'url' ), response.data.attachmentVersion ) );

						const	sizes = model.get( 'sizes' );

						for ( const size of Object.keys( sizes ) ) { // eslint-disable-line no-unused-vars
							sizes[ size ].url = imageVersion( sizes[ size ].url, response.data.attachmentVersion );
						}

						model.set( 'sizes', sizes );
					}

					model.set( 'hasBackup', response.data.hasBackup ? true : false );

					processed++;
					status.set( { processed } );
					model.trigger( 'ewBulkAction:done' );

					if ( status.get( 'total' ) === processed ) {
						this.ewBulkActionDone();
					} else {
						this.ewBulkActionRecursive();
					}
				} else {
					const error = ( 'string' === typeof response.data.message ) ? response.data.message : ew.i18n.genericErrorMessage;
					this.ewBulkActionError( error );
				}
			} ).fail( () => {
				this.ewBulkActionError( ew.i18n.genericErrorMessage );
			} );
		}

		ewWatermark() {
			this.state().set( 'ewAction', 'watermark' );
			this.state().set( 'ewSuccessMessage', ew.i18n.watermarkingSuccessMessage );
			this.state().set( 'ewStatusText', ew.i18n.watermarkingStatus );
			this.ewBulkAction();
		}

		ewRestoreBackup() {
			this.state().set( 'ewAction', 'restore' );
			this.state().set( 'ewSuccessMessage', ew.i18n.restoringSuccessMessage );
			this.state().set( 'ewStatusText', ew.i18n.restoringStatus );
			this.ewBulkAction();
		}

		ewBulkActionError( error ) {
			const status = this.state().get( 'ewStatus' );

			status.set( { error } );

			this.ewBulkActionDone();
		}

		ewBulkActionDone() {
			const
				state = this.state(),
				status = state.get( 'ewStatus' ),
				currentModel = state.get( 'ewCurrentModel' ),
				procesed = status.get( 'processed' ),
				error = status.get( 'error' ),
				successMessage = state.get( 'ewSuccessMessage' );

			this.deactivateMode( 'watermarking' );
			this.deactivateMode( 'restoring' );

			if ( procesed > 0 ) {
				addNotice( successMessage.replace( '{procesed}', procesed ), 'success' );
			}

			if ( error ) {
				const errorMessage = ew.i18n.bulkActionErrorMessage
					.replace( '{imageTitle}', currentModel.get( 'title' ) )
					.replace( '{error}', error );
				addNotice( errorMessage, 'error' );
			}
		}

		ewCancelBulkAction() {
			const
				state = this.state(),
				collection = state.get( 'ewCollection' ),
				currentModel = state.get( 'ewCurrentModel' );

			if ( currentModel ) {
				collection.push( currentModel );
			}

			for ( const model of collection.models ) { // eslint-disable-line no-unused-vars
				model.trigger( 'ewBulkAction:canceled' );
			}
		}
	};
}
