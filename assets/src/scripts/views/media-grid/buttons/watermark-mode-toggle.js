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

			this.model.set( {
				originalText: this.model.get( 'text' ),
				cancelText: this.options.cancelText,
			} );
			delete this.options.cancelText;

			this.controller.on( 'selection:toggle', this.update, this );
			this.controller.on( 'watermark:activate', () => this.$el.html( this.model.get( 'cancelText' ) ) );
			this.controller.on( 'watermark:deactivate', this.update, this );
		}

		render() {
			super.render();

			if ( this.controller.isModeActive( 'select' ) ) {
				this.$el.addClass( 'watermark-mode-toggle-button' );
			} else {
				this.$el.addClass( 'watermark-mode-toggle-button hidden' );
			}

			this.update();

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

		update() {
			if ( this.controller.isModeActive( 'watermark' ) ) {
				return;
			}

			const
				lastSelectionCount = this.model.get( 'filteredSelectionCount' ),
				filteredSelectionCount = filterSelection( this.controller.state().get( 'selection' ), false, false );

			if ( filteredSelectionCount !== lastSelectionCount ) {
				this.model.set( {
					filteredSelectionCount,
					text: `${ this.model.get( 'originalText' ) } (${ filteredSelectionCount })`,
				} );

				this.model.set( 'disabled', ! Boolean( filteredSelectionCount ) );

				if ( ! this.controller.state().get( 'selection' ).length ) {
					this.controller.deactivateMode( 'watermark' );
				}
			}
		}
	}

	WatermarkModeExport = WatermarkModeButton;
}

export default WatermarkModeExport;
