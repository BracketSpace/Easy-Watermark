/**
 * Internal dependencies
 */
import { filterSelection } from '../../../utils/functions';

/* global wp */

let WatermarkModeExport = null;

if ( wp.media && 'function' === typeof wp.media.view.Button ) {
	class WatermarkModeButton extends wp.media.view.Button {
		initialize() {
			super.initialize();

			this.model.set( 'cancelText', this.options.cancelText );
			delete this.options.cancelText;

			this.controller.on( 'selection:toggle', this.toggleDisabled, this );

			this.controller.on( 'watermark:activate', () => {
				this.$el.html( this.model.get( 'cancelText' ) );
			} );

			this.controller.on( 'watermark:deactivate', () => {
				this.$el.html( this.model.get( 'text' ) );
			} );
		}

		render() {
			super.render();

			if ( this.controller.isModeActive( 'select' ) ) {
				this.$el.addClass( 'watermark-mode-toggle-button' );
			} else {
				this.$el.addClass( 'watermark-mode-toggle-button hidden' );
			}

			this.toggleDisabled();

			return this;
		}

		click() {
			super.click();

			if ( this.controller.isModeActive( 'watermark' ) ) {
				this.controller.deactivateMode( 'watermark' );
			} else {
				this.controller.activateMode( 'watermark' );

				filterSelection( this.controller.state().get( 'selection' ) );
			}
		}

		toggleDisabled() {
			this.model.set( 'disabled', ! filterSelection( this.controller.state().get( 'selection' ), false, false ) );

			if ( ! this.controller.state().get( 'selection' ).length ) {
				this.controller.deactivateMode( 'watermark' );
			}
		}
	}

	WatermarkModeExport = WatermarkModeButton;
}

export default WatermarkModeExport;
