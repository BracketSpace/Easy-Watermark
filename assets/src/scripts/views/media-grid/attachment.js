/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { filterSelection, isImage } from '../../utils/functions.js';

/* global wp, ew */

if ( wp.media && 'function' === typeof wp.media.view.Attachment.Library ) {
	const Library = wp.media.view.Attachment.Library;
	wp.media.view.Attachment.Library = Library.extend( {
		initialize() {
			Library.prototype.initialize.apply( this, arguments );

			this.listenTo( this.model, 'ewBulkAction:start', this.showLoader );
			this.listenTo( this.model, 'ewBulkAction:done', this.render );

			this.controller.on( 'selection:toggle watermark:activate processing:activate', this.disable, this );
			this.controller.on( 'watermark:deactivate processing:deactivate', this.enable, this );
		},

		render() {
			Library.prototype.render.apply( this, arguments );

			this.$el.append( $( '<span></span>' ).addClass( 'spinner' ) );
		},

		toggleSelection( { method } ) {
			if ( ! this.controller.isModeActive( 'watermark' ) ||
				( ( isImage( this.model ) && ! this.model.get( 'usedAsWatermark' ) ) || 'between' === method ) ) {
				// In watermark mode only select images.
				Library.prototype.toggleSelection.apply( this, arguments );
			}

			if ( this.controller.isModeActive( 'watermark' ) ) {
				if ( ! isImage( this.model ) || this.model.get( 'usedAsWatermark' ) ) {
					this.$el.blur();
				}

				if ( 'between' === method ) {
					filterSelection( this.options.selection );
				}
			}
		},

		showLoader() {
			this.$el.find( '.spinner' ).css( { visibility: 'visible' } );
		},

		disable() {
			if ( ! this.controller.isModeActive( 'watermark' ) && ! this.controller.isModeActive( 'processing' ) ) {
				return;
			}

			if ( this.hasBadge ) {
				return;
			}

			if ( this.controller.isModeActive( 'processing' ) && ! this.wasSelected() ) {
				return;
			}

			let text;

			if ( ! isImage( this.model ) ) {
				text = ew.i18n.notSupported;
			} else if ( this.model.get( 'usedAsWatermark' ) ) {
				text = ew.i18n.usedAsWatermark;
			} else if ( this.controller.isModeActive( 'restoring' ) && ! this.model.get( 'hasBackup' ) ) {
				text = ew.i18n.noBackupAvailable;
			} else {
				return;
			}

			const badge = $( '<div>', { class: 'badge' } ).text( text );

			this.$el.addClass( 'disabled' ).append( badge );
			this.hasBadge = true;
		},

		enable() {
			if ( ! this.controller.isModeActive( 'watermark' ) && ! this.controller.isModeActive( 'processing' ) ) {
				this.$el.removeClass( 'disabled' ).find( '.badge' ).remove();
				this.hasBadge = false;
			}
		},

		wasSelected() {
			const selection = this.controller.state().get( 'originalSelection' );

			if ( selection ) {
				return !! selection.get( this.model.cid );
			}
		},
	} );
}
