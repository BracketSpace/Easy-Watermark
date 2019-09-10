/* global wp */

if ( wp.media && 'function' === typeof wp.media.view.SelectModeToggleButton ) {
	wp.media.view.SelectModeToggleButton = class extends wp.media.view.SelectModeToggleButton {
		initialize() {
			super.initialize();

			this.controller.on( 'processing:activate processing:deactivate', this.toggleDisabled, this );
		}

		toggleDisabled() {
			this.model.set( 'disabled', this.controller.isModeActive( 'processing' ) );
		}

		toggleBulkEditHandler() {
			super.toggleBulkEditHandler();

			this.controller.trigger( 'selection:toggle' );

			const toolbar = this.controller.content.get().toolbar;

			if ( this.controller.isModeActive( 'select' ) ) {
				toolbar.$( '.watermark-mode-toggle-button' ).removeClass( 'hidden' );
			} else {
				toolbar.$( '.watermark-mode-toggle-button' ).addClass( 'hidden' );
			}

			toolbar.$( '.watermark-selector' ).css( 'display', '' );
			toolbar.$( '.ew-status' ).css( 'display', '' );
		}
	};
}
