/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import '../styles/uploader.scss';

$( document ).ready( () => {
	if ( typeof wp !== 'undefined' && typeof wp.Uploader === 'function' ) {
		const Uploader = wp.Uploader;

		wp.Uploader = class extends Uploader {
			init() {
				super.init();

				this.switch = $( '.ew-watermark-all-switch' );

				if ( this.switch.length ) {
					$( 'body' ).on(
						'change',
						'.ew-watermark-all-switch input',
						this.updateAutoWatermarkParam.bind( this )
					);

					this.param( 'auto_watermark', ew.autoWatermark );
				} else {
					this.param( 'auto_watermark', false );
				}
			}

			updateAutoWatermarkParam( e ) {
				ew.autoWatermark = $( e.target ).is( ':checked' );

				this.param( 'auto_watermark', ew.autoWatermark );
			}
		};
	}

	if (
		typeof wp !== 'undefined' &&
		wp.media &&
		typeof wp.media.view.UploaderInline === 'function'
	) {
		const UploaderInline = wp.media.view.UploaderInline;

		wp.media.view.UploaderInline = UploaderInline.extend( {
			render() {
				UploaderInline.prototype.render.apply( this, arguments );

				if ( ! this.$el.hasClass( 'hidden' ) ) {
					this.$el
						.find( '.ew-watermark-all-switch input' )
						.prop( 'checked', ew.autoWatermark );
				}
			},
		} );
	}
} );
