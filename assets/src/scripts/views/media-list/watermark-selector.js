/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import View from './view';

/* global ew */

export default class extends View {
	tagName() {
		return 'select';
	}

	className() {
		return 'ew-status';
	}

	events() {
		return {
			change: 'selectWatermark',
		};
	}

	constructor( options ) {
		super( options );

		if ( this.controller ) {
			this.listenTo( this.controller, 'change', this.update );
		}
	}

	render() {
		super.render();

		this.$el
			.append( $( '<option>', { value: '' } ).html( ew.i18n.selectWatermarkLabel ) )
			.append( $( '<option>', { value: 'all' } ).html( ew.i18n.allWatermarksLabel ) );

		for ( const id in ew.watermarks ) { // eslint-disable-line no-unused-vars
			this.$el.append( $( '<option>', { value: id } ).html( ew.watermarks[ id ] ) );
		}

		this.attach();

		return this;
	}

	update() {
		if ( ! this.bulkActionSelector.is( this.controller.get( 'select' ) ) ) {
			return;
		}

		if ( 'watermark' === this.controller.get( 'action' ) ) {
			this.$el.show();
		} else {
			this.$el.hide();
		}
	}

	attach() {
		this.bulkActionSelector.after( this.$el );
		this.$el.hide();

		return this;
	}

	selectWatermark() {
		this.controller.set( 'watermark', this.$el.val() );
	}
}
