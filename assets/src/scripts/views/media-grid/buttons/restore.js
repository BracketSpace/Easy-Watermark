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

			this.model.set( {
				originalText: this.model.get( 'text' ),
			} );

			this.controller.on( 'selection:toggle', this.update, this );

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

			this.update();

			return this;
		}

		click() {
			super.click();

			if ( ! this.controller.isModeActive( 'select' ) ) {
				return;
			}

			this.controller.ewRestoreBackup();
		}

		update() {
			const
				lastSelectionCount = this.model.get( 'filteredSelectionCount' ),
				filteredSelectionCount = filterSelection( this.controller.state().get( 'selection' ), true, false );

			if ( filteredSelectionCount !== lastSelectionCount ) {
				this.model.set( {
					filteredSelectionCount,
					text: `${ this.model.get( 'originalText' ) } (${ filteredSelectionCount })`,
				} );

				this.model.set( 'disabled', ! Boolean( filteredSelectionCount ) );
			}
		}
	};
}

export default RestoreButton;
