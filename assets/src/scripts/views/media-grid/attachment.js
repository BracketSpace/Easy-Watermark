/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { filterSelection } from '../../utils/functions.js';

/* global wp, ew */

if ( wp.media && 'function' === typeof wp.media.view.Attachment.Library ) {
	wp.media.view.Attachment.Library = class extends wp.media.view.Attachment.Library {
		initialize() {
			super.initialize();

			this.listenTo( this.model, 'ewBulkAction:start', this.showLoader );
			this.listenTo( this.model, 'ewBulkAction:done ewBulkAction:canceled', this.render );
		}

		render() {
			super.render();

			this.$el.append( $( '<span></span>' ).addClass( 'spinner' ) );
		}

		toggleSelection( options ) {
			const { method } = options;

			if ( ! this.controller.isModeActive( 'watermark' ) ||
				( ( this.isImage() && ! this.model.get( 'usedAsWatermark' ) ) || 'between' === method ) ) {
				// In watermark mode only select images.
				super.toggleSelection( options );
			}

			if ( this.controller.isModeActive( 'watermark' ) ) {
				if ( ! this.isImage() || this.model.get( 'usedAsWatermark' ) ) {
					this.$el.blur();
				}

				if ( 'between' === method ) {
					filterSelection( this.options.selection );
				}
			}
		}

		isImage() {
			return Object.keys( ew.mime ).includes( this.model.get( 'mime' ) );
		}

		showLoader() {
			this.$el.find( '.spinner' ).css( { visibility: 'visible' } );
		}
	};
}
