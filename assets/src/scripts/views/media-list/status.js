/**
 * Internal dependencies
 */
import View from './view';

export default class extends View {
	tagName() {
		return 'p';
	}

	className() {
		return 'ew-status';
	}

	template() {
		let text = this.status.get( 'text' );

		if ( this.status.get( 'processing' ) ) {
			const
				processed = this.status.get( 'processed' ),
				total = this.status.get( 'total' ),
				counter = `${ processed }/${ total }`;

			let percent = Math.floor( processed / total * 100 );

			if ( 'string' === typeof text ) {
				text = text.replace( '{counter}', counter );
			}

			if ( isNaN( percent ) ) {
				percent = 0;
			}

			text = `${ text } (${ percent }%)`;
		}

		return text;
	}

	constructor( options ) {
		super( options );

		this.status = this.controller.status();

		this.listenTo( this.status, 'change', this.update );
	}

	render() {
		super.render();
		this.attach();
	}

	update() {
		if ( ! this.bulkActionSelector.is( this.controller.get( 'select' ) ) ) {
			return;
		}

		if ( this.status.get( 'text' ) ) {
			this.$el.html( this.template() );
			this.$el.show();
		} else {
			this.$el.hide();
		}
	}

	attach() {
		this.bulkActionSelector.parent().append( this.$el );
		this.$el.hide();

		return this;
	}
}
