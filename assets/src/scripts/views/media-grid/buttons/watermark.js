/* global wp */

let WatermarkButton = null;

if ( wp.media && 'function' === typeof wp.media.view.Button ) {
	WatermarkButton = class extends wp.media.view.Button {
		initialize() {
			super.initialize();

			this.controller.on( 'watermark:activate', () => {
				this.$el.removeClass( 'hidden' ).show();
			} );

			this.controller.on( 'watermark:deactivate', () => {
				this.$el.addClass( 'hidden' ).hide();
			} );

			this.controller.on( 'watermark:selected', this.toggleDisabled, this );

			this.model.set( 'disabled', true );
		}

		render() {
			super.render();

			if ( this.controller.isModeActive( 'watermark' ) ) {
				this.$el.addClass( 'watermark-button' );
			} else {
				this.$el.addClass( 'watermark-button hidden' );
			}

			return this;
		}

		click() {
			super.click();

			if ( ! this.controller.state().get( 'watermark' ) ) {
				return;
			}

			this.controller.ewWatermark();
		}

		toggleDisabled( watermark ) {
			this.model.set( 'disabled', ! watermark.length );
		}
	};
}

export default WatermarkButton;
