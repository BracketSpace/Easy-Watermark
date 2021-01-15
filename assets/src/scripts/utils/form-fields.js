/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.fields = $( '.form-field' );
		this.buttons = this.fields.find( 'button[data-toggle=dropdown]' );
		this.dropdowns = this.fields.find( '.dropdown-menu[data-target]' );
		this.links = this.dropdowns.find( 'a' );

		this.init();
	}

	init() {
		this.toggleDropdown = this.toggleDropdown.bind( this );
		this.dropdownSelect = this.dropdownSelect.bind( this );
		this.hideDropdowns = this.hideDropdowns.bind( this );

		this.buttons.on( 'click', this.toggleDropdown );
		this.links.on( 'click', this.dropdownSelect );
		$( document ).on( 'click', this.hideDropdowns );
	}

	toggleDropdown( e ) {
		e.preventDefault();

		const button = $( e.target ),
			position = button.position();

		button.toggleClass( 'is-open' );

		button.next( '.dropdown-menu' ).css( {
			left: position.left,
			top: position.top + button.height(),
		} ).toggle();
	}

	dropdownSelect( e ) {
		e.preventDefault();

		const item = $( e.target ),
			dropdown = item.closest( '.dropdown-menu' ),
			target = $( dropdown.data( 'target' ) );

		if ( target.length ) {
			target.val( item.data( 'value' ) );
			dropdown.prev( 'button[data-toggle=dropdown]' ).text( item.text() );
		}
	}

	hideDropdowns( e ) {
		const item = $( e.target );

		if ( item.is( this.buttons ) ) {
			return;
		}

		this.buttons.removeClass( 'is-open' );

		this.dropdowns.hide();
	}
}
