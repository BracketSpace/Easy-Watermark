/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.metabox = $( '#scaling' );
		this.scalingModeSelect = this.metabox.find( '#watermark-scaling-mode' );
		this.hiddenSections = this.metabox.find( '.hidden' );
		this.fields = this.metabox.find( 'input, select' );

		this.toggleOptionsVisibility = this.toggleOptionsVisibility.bind( this );

		this.scalingModeSelect.on( 'change', this.toggleOptionsVisibility );

		this.toggleOptionsVisibility();
	}

	enable( type ) {
		if ( type === 'image' ) {
			this.metabox.fadeIn( 200 );
			this.fields.prop( 'disabled', false );
		} else {
			this.metabox.hide();
			this.fields.prop( 'disabled', true );
		}
	}

	toggleOptionsVisibility() {
		this.hiddenSections.hide();

		switch ( this.scalingModeSelect.val() ) {
			case 'fit_to_width' :
			case 'fit_to_height' :
				this.hiddenSections.show();
				break;
			case 'cover' :
			case 'contain' :
				this.hiddenSections.filter( '.show-for-all' ).show();
				break;
		}
	}
}
