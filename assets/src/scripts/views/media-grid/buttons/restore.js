/**
 * Internal dependencies
 */
import { filterSelection } from '../../../utils/functions';

/* global wp */

let RestoreButton = null;

if ( wp.media && 'function' === typeof wp.media.view.Button ) {
	RestoreButton = class extends wp.media.view.Button {
		initialize() {
			super.initialize();

			this.controller.on( 'selection:toggle', this.toggleDisabled, this );

			this.controller.on( 'watermark:activate watermark:deactivate', this.render, this );
			this.controller.on( 'select:activate select:deactivate', this.render, this );
		}

		render() {
			super.render();

			if ( this.controller.isModeActive( 'select' ) && ! this.controller.isModeActive( 'watermark' ) ) {
				this.$el.addClass( 'restore-button' );
			} else {
				this.$el.addClass( 'restore-button hidden' );
			}

			this.toggleDisabled();

			return this;
		}

		click() {
			super.click();

			if ( ! this.controller.isModeActive( 'select' ) ) {
				return;
			}

			const selection = this.controller.state().get( 'selection' );

			filterSelection( selection, true );

			if ( selection.length ) {
				this.controller.activateMode( 'restoring' );
			}
		}

		toggleDisabled() {
			this.model.set( 'disabled', ! filterSelection( this.controller.state().get( 'selection' ), true, false ) );
		}
	};
}

export default RestoreButton;
