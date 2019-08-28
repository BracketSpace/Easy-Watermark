/**
 * Internal dependencies
 */
import WatermarkButton from './buttons/watermark.js';
import RestoreButton from './buttons/restore.js';
import WatermarkModeToggleButton from './buttons/watermark-mode-toggle.js';
import WatermarkSelector from './watermark-selector.js';
import WatermarkingStatus from './watermarking-status.js';

/* global wp, ew */

if ( wp.media && 'function' === typeof wp.media.view.AttachmentsBrowser ) {
	wp.media.view.AttachmentsBrowser = class extends wp.media.view.AttachmentsBrowser {
		createToolbar() {
			super.createToolbar();

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

			this.toolbar.set( 'restoreButton', new RestoreButton( {
				text: ew.i18n.restoreButtonLabel,
				controller: this.controller,
				priority: -30,
			} ).render() );

			this.toolbar.set( 'watermarkingStatus', new WatermarkingStatus( {
				style: 'primary',
				controller: this.controller,
				priority: -20,
			} ).render() );

			this.controller.on( 'select:deactivate', () => this.controller.deactivateMode( 'watermark' ) );
			this.controller.on( 'watermark:activate', this.hideButtons, this );
			this.controller.on( 'watermark:deactivate', this.showButtons, this );
			this.controller.on( 'watermarking:activate watermarking:deactivate', this.disableViewSwitch, this );
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
