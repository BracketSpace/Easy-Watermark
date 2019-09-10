/**
 * Internal dependencies
 */
import Tool from './tool';

export default class extends Tool {
	constructor( options ) {
		options.el = '.tool-bulk-watermark';

		super( options );

		this.action = 'watermark';

		this.selectWatermark = this.selectWatermark.bind( this );
		this.toggleButton = this.toggleButton.bind( this );

		this.select = this.$el.find( 'select' );
		this.select.on( 'change', this.selectWatermark );

		this.toggleButton();
	}

	selectWatermark() {
		const
			watermark = this.select.val(),
			nonce = this.select.find( 'option:selected' ).data( 'nonce' );

		this.state.set( {
			watermark,
			nonce,
		} );

		this.toggleButton();
	}

	toggleButton() {
		if ( '-1' === this.select.val() ) {
			this.button.addClass( 'disabled' );
		} else {
			this.button.removeClass( 'disabled' );
		}
	}

	disable() {
		super.disable();
		this.select.prop( { disabled: true } );
	}

	processing() {
		this.select.val( '-1' );
		super.processing();
	}

	reset() {
		super.reset();
		this.select.prop( { disabled: false } ).change();
	}
}
