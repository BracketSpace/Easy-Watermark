/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import '../styles/media-library.scss';

/* global wp, ew */

if ( typeof wp !== 'undefined' && typeof wp.Uploader === 'function' && typeof wp.media.view.UploaderInline === 'function' ) {
	class Uploader extends wp.Uploader {
		init() {
			super.init();

			this.updateAutoWatermarkParam = this.updateAutoWatermarkParam.bind( this );

			$( 'body' ).on( 'change', '.ew-watermark-all-switch input', this.updateAutoWatermarkParam );

			this.param( 'auto_watermark', ew.autoWatermark );
		}

		updateAutoWatermarkParam( e ) {
			ew.autoWatermark = $( e.target ).is( ':checked' );

			this.param( 'auto_watermark', ew.autoWatermark );
		}
	}

	class UploaderInline extends wp.media.view.UploaderInline {
		render() {
			super.render();

			if ( ! this.$el.hasClass( 'hidden' ) ) {
				this.$el.find( '.ew-watermark-all-switch input' ).prop( 'checked', ew.autoWatermark );
			}
		}
	}

	wp.Uploader = Uploader;
	wp.media.view.UploaderInline = UploaderInline;
}
