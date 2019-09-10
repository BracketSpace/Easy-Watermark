/**
 * External dependencies
 */
import $ from 'jquery';

/* global wp, ew */

let WatermarkSelectorExport = null;

if ( wp.media && 'function' === typeof wp.media.View ) {
	class WatermarkSelector extends wp.media.View {
		constructor( options ) {
			super( options );

			this.controller.on( 'watermark:activate', this.show, this );
			this.controller.on( 'watermark:deactivate', this.hide, this );
		}

		events() {
			return {
				change: 'selectWatermark',
			};
		}

		tagName() {
			return 'select';
		}

		selectWatermark() {
			this.controller.trigger( 'watermark:selected', this.$el.val() );
			this.controller.state().set( 'watermark', this.$el.val() );
		}

		show() {
			this.$el.removeClass( 'hidden' );
		}

		hide() {
			this.$el.addClass( 'hidden' );
		}

		render() {
			super.render();

			this.$el.append( $( '<option>', { value: '' } ).html( ew.i18n.selectWatermarkLabel ) );

			if ( ew.watermarks ) {
				if ( 1 < Object.keys( ew.watermarks ).length ) {
					this.$el.append( $( '<option>', { value: 'all' } ).html( ew.i18n.allWatermarksLabel ) );
				}

				for ( const id in ew.watermarks ) { // eslint-disable-line no-unused-vars
					this.$el.append( $( '<option>', { value: id } ).html( ew.watermarks[ id ] ) );
				}
			}

			if ( this.controller.isModeActive( 'select' ) ) {
				this.$el.addClass( 'watermark-selector' );
			} else {
				this.$el.addClass( 'watermark-selector hidden' );
			}

			return this;
		}
	}

	WatermarkSelectorExport = WatermarkSelector;
}

export default WatermarkSelectorExport;
