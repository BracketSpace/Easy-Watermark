/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.metabox = $( '#applying-rules' );
		this.autoAddCheckbox = this.metabox.find( '#watermark-autoadd' );
		this.hiddenSections = this.metabox.find( '.hidden' );

		this.toggleOptionsVisibility = this.toggleOptionsVisibility.bind( this );

		this.autoAddCheckbox.on( 'change', this.toggleOptionsVisibility );

		this.toggleOptionsVisibility();
	}

	enable() {
		this.metabox.fadeIn( 200 );
	}

	toggleOptionsVisibility() {
		if ( this.autoAddCheckbox.prop( 'checked' ) ) {
			this.hiddenSections.show();
		} else {
			this.hiddenSections.hide();
		}
	}
}
