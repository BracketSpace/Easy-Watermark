/* global wp */

let StatusExport = null;

if ( wp.media && 'function' === typeof wp.media.View ) {
	class Status extends wp.media.View {
		tagName() {
			return 'p';
		}

		className() {
			return 'ew-status';
		}

		template() {
			let statusText = this.status.get( 'text' );

			if ( this.status.get( 'progress' ) ) {
				const
					processed = this.status.get( 'processed' ),
					total = this.status.get( 'total' ),
					counter = `${ processed }/${ total }`,
					percent = Math.floor( processed / total * 100 );

				if ( 'string' === typeof status ) {
					statusText = statusText.replace( '{counter}', counter );
				}

				statusText += `  (${ percent }%)`;
			}

			return `<span class="status">${ statusText }</span>`;
		}

		constructor( options ) {
			super( options );

			this.status = this.controller.state().get( 'ewStatus' );
			this.status.on( 'change', this.update, this );
		}

		render() {
			this.update();

			return this;
		}

		update() {
			if ( ! this.status.get( 'visible' ) ) {
				this.$el.addClass( 'hidden' );
				return;
			}

			this.$el.removeClass( 'hidden' ).html( this.template() );
		}

		cancel( e ) {
			e.preventDefault();
			this.controller.deactivateMode( 'watermarking' );
		}
	}

	StatusExport = Status;
}

export default StatusExport;
