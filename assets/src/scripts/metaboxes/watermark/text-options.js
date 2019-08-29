/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.metabox = $( '#text-options' );
		this.form = $( 'form#post' );
		this.colorInput = this.metabox.find( '#text-color' );
		this.fields = this.metabox.find( 'input, select' );

		this.colorChangeTimeout = null;

		this.colorChanged = this.colorChanged.bind( this );

		this.colorInput.wpColorPicker( {
			palettes: true,
			change: this.colorChanged,
		} );
	}

	colorChanged() {
		clearTimeout( this.colorChangeTimeout );

		this.colorChangeTimeout = setTimeout( () => {
			this.form.trigger( 'ew.save' );
		}, 500 );
	}

	enable( type ) {
		if ( type === 'text' ) {
			this.metabox.fadeIn( 200 );
			this.fields.prop( 'disabled', false );
		} else {
			this.metabox.hide();
			this.fields.prop( 'disabled', true );
		}
	}
}
