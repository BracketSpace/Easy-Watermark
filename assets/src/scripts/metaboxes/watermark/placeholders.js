/**
 * External dependencies
 */
import $ from 'jquery';
import Clipboard from 'clipboard';

export default class {
	constructor() {
		this.metabox = $( '#placeholders' );
		this.placeholders = this.metabox.find( '.placeholders-list li' );
		this.searchField = this.metabox.find( 'input.ew-search-placeholders' );

		this.clipboard = new Clipboard( '.placeholders-list code' );

		this.clipboardSuccess = this.clipboardSuccess.bind( this );
		this.filterPlaceholders = this.filterPlaceholders.bind( this );

		this.clipboard.on( 'success', this.clipboardSuccess );
		this.searchField.on( 'keyup', this.filterPlaceholders );
	}

	enable( type ) {
		if ( type === 'text' ) {
			this.metabox.fadeIn( 200 );
		} else {
			this.metabox.hide();
		}
	}

	clipboardSuccess( e ) {
		const item = $( e.trigger );

		item.text( 'Copied' );

		setTimeout( () => {
			item.text( e.text );
		}, 1000 );
	}

	filterPlaceholders( e ) {
		const val = $( e.target ).val();

		this.placeholders.hide().each( ( n, item ) => {
			const $item = $( item ),
				text = $item.find( 'code' ).text().toLowerCase();

			if ( text.indexOf( val ) !== -1 ) {
				$item.show();
			}
		} );
	}
}
