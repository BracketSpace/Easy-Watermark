/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import { confirm } from '../includes/vex';

/* global ew */

export default class {
	constructor() {
		this.wrap = $( '.watermarks' );

		if ( this.wrap.length ) {
			this.init();
		}
	}

	init() {
		this.confirm = this.confirm.bind( this );

		this.deleteButtons = this.wrap.find( '.row-actions a.submitdelete' );

		this.deleteButtons.on( 'click', this.confirm );
	}

	confirm( e ) {
		e.preventDefault();

		const
			link = $( e.currentTarget ),
			watermarkName = link.data( 'watermark-name' ),
			message = ew.i18n.deleteConfirmation.replace( '{watermarkName}', watermarkName );

		confirm( message, ( result ) => {
			if ( true === result ) {
				window.location = link.attr( 'href' );
			}
		} );
	}
}
