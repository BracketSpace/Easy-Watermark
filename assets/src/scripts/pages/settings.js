/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.form = $( 'form#easy-watermark-settings-form' );

		if ( this.form.length ) {
			this.init();
		}
	}

	init() {
		this.toggleGroup = this.toggleGroup.bind( this );

		this.checkboxes = this.form.find( 'input[data-toggle]' );
		this.checkboxes.on( 'change', this.toggleGroup );
		this.checkboxes.change();
	}

	toggleGroup( e ) {
		const
			checkbox = $( e.target ),
			group = checkbox.data( 'toggle' ),
			rows = this.form.find( `.group-${ group }` ),
			fields = rows.find( 'input, textarea, select' );

		if ( true === checkbox.is( ':checked' ) ) {
			rows.show();
			fields.prop( 'disabled', false );
		} else {
			rows.hide();
			fields.prop( 'disabled', true );
		}
	}
}
