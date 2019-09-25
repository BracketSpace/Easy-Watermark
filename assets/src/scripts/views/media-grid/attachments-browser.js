/**
 * Internal dependencies
 */
import WatermarkButton from './buttons/watermark.js';
import RestoreButton from './buttons/restore.js';
import WatermarkModeToggleButton from './buttons/watermark-mode-toggle.js';
import WatermarkSelector from './watermark-selector.js';
import Status from './status.js';

/* global wp, ew */

if ( wp.media && 'function' === typeof wp.media.view.AttachmentsBrowser ) {
	wp.media.view.AttachmentsBrowser = class extends wp.media.view.AttachmentsBrowser {
		createToolbar() {
			super.createToolbar();

			if ( ! this.controller.state().get( 'ewStatus' ) ) {
				/**
				 * If there is no 'ewStatus' in controller, Media Library has been
				 * obviously replaced by some plugin (and not extended, as we do in EW).
				 * This is the case with Enhanced Media Library plugin.
				 *
				 * In this situation our modifications are not present in controller,
				 * so our views loaded below might cause errors.
				 */
				return;
			}

			if ( Object.keys( ew.watermarks ).length ) {
				this.toolbar.set( 'watermarkModeToggleButton', new WatermarkModeToggleButton( {
					text: ew.i18n.watermarkModeToggleButtonLabel,
					cancelText: ew.i18n.cancelLabel,
					controller: this.controller,
					priority: -60,
				} ).render() );

				this.toolbar.set( 'watermarkSelector', new WatermarkSelector( {
					controller: this.controller,
					priority: -50,
				} ).render() );

				this.toolbar.set( 'watermarkButton', new WatermarkButton( {
					text: ew.i18n.watermarkButtonLabel,
					style: 'primary',
					controller: this.controller,
					priority: -40,
				} ).render() );
			}

			this.toolbar.set( 'restoreButton', new RestoreButton( {
				text: ew.i18n.restoreButtonLabel,
				controller: this.controller,
				priority: -30,
			} ).render() );

			this.toolbar.set( 'watermarkingStatus', new Status( {
				style: 'primary',
				controller: this.controller,
				priority: -20,
			} ).render() );

			this.controller.on( 'select:deactivate', () => this.controller.deactivateMode( 'watermark' ) );
			this.controller.on( 'watermark:activate', this.hideButtons, this );
			this.controller.on( 'watermark:deactivate', this.showButtons, this );
			this.controller.on( 'processing:activate processing:deactivate', this.disableViewSwitch, this );
		}

		hideButtons() {
			this.$( '.select-mode-toggle-button' ).addClass( 'hidden' );
			this.$( '.delete-selected-button' ).addClass( 'hidden' );
		}

		showButtons() {
			this.$el.html( this.model.get( 'text' ) );
			this.$( '.select-mode-toggle-button' ).removeClass( 'hidden' );
			this.$( '.delete-selected-button' ).removeClass( 'hidden' );
		}

		disableViewSwitch() {
			const viewSwitch = this.toolbar.$( '.view-switch' );

			if ( viewSwitch.hasClass( 'disabled' ) ) {
				viewSwitch.removeClass( 'disabled' );
			} else {
				viewSwitch.addClass( 'disabled' );
			}
		}
	};
}
