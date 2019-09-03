/**
 * Internal dependencies
 */
import View from './view';
import { isImage } from '../../utils/functions.js';

/**
 * External dependencies
 */
import $ from 'jquery';

/* global ew */

export default class extends View {
	constructor( options ) {
		super( options );

		this.model = options.model;

		this.listenTo( this.model, 'processing:start', this.showSpinner );
		this.listenTo( this.model, 'processing:done', this.reset );
		this.listenTo( this.model, 'remove', this.deselect );

		this.controller.on( 'bulkAction:finished', this.reset, this );
	}

	showSpinner() {
		this.getSpinner().appendTo( this.$el.find( 'span.media-icon' ) );
	}

	getSpinner() {
		if ( ! this.spinner ) {
			this.spinner = $( '<span>', { class: 'spinner ew-spinner' } );
		}

		return this.spinner;
	}

	reset() {
		if ( this.spinner ) {
			this.spinner.remove();
		}

		this.getStatus().text( '' );
		this.$el.find( 'input[type="checkbox"]' ).click().prop( 'checked', false );
	}

	deselect() {
		if ( this.controller.status().get( 'processing' ) ) {
			return;
		}

		this.$el.find( 'input[type="checkbox"]' ).click().prop( 'checked', false );

		let text;

		if ( ! isImage( this.model ) ) {
			text = ew.i18n.notSupported;
		} else if ( this.model.get( 'usedAsWatermark' ) ) {
			text = ew.i18n.usedAsWatermark;
		} else if ( 'restore' === this.controller.get( 'action' ) && ! this.model.get( 'hasBackup' ) ) {
			text = ew.i18n.noBackupAvailable;
		}

		this.getStatus().text( ` - ${ text }` );
	}

	getStatus() {
		if ( ! this.status ) {
			this.status = $( '<span>', { class: 'ew-status' } );
			this.$el.find( 'strong.has-media-icon' ).append( this.status );
		}

		return this.status;
	}
}
