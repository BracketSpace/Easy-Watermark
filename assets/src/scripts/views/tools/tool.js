/**
 * External dependencies
 */
import { View } from 'backbone';

export default class extends View {
	constructor( options ) {
		super( options );

		this.state = options.state;
		this.callback = options.callback;

		this.handleClick = this.handleClick.bind( this );

		this.button = this.$el.find( 'a' );
		this.spinner = this.$el.find( '.spinner' );
		this.content = this.$el.find( '.content' );
		this.status = this.$el.find( '.status' );

		this.button.on( 'click', this.handleClick );

		this.mode = this.state.get( 'mode' );
		this.state.on( 'change', this.update, this );
	}

	template() {
		const
			processed = this.state.get( 'processed' ),
			total = this.state.get( 'total' ),
			percent = Math.floor( processed / total * 100 );

		let text = this.state.get( 'statusText' );

		text = text.replace( '{counter}', `${ processed }/${ total }` );
		return `${ text } (${ percent }%)`;
	}

	handleClick() {
		if ( ! this.button.hasClass( 'disabled' ) ) {
			this.callback();
		}
	}

	update() {
		const
			mode = this.state.get( 'mode' ),
			action = this.state.get( 'action' );

		if ( this.mode !== mode ) {
			this.toggleMode( mode );
		}

		if ( 'processing' === mode && this.action === action ) {
			this.status.html( this.template() );
		}
	}

	toggleMode( mode ) {
		this.mode = mode;

		if ( this.action !== this.state.get( 'action' ) && ( 'loading' === mode || 'processing' === mode ) ) {
			this.disable();
		} else if ( 'loading' === mode ) {
			this.loading();
		} else if ( 'processing' === mode ) {
			this.processing();
		} else {
			this.reset();
		}
	}

	disable() {
		this.button.addClass( 'disabled' );
	}

	loading() {
		this.disable();
		this.spinner.css( { visibility: 'visible' } );
	}

	processing() {
		this.content.hide();
		this.status.show();
	}

	reset() {
		this.spinner.css( { visibility: 'hidden' } );
		this.status.hide();
		this.content.show();
		this.button.removeClass( 'disabled' );
	}
}
