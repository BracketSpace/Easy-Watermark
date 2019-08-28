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

		template( processed, total ) {
			const
				counter = `${ processed }/${ total }`,
				percent = Math.floor( processed / total * 100 );

			let status = this.controller.state().get( 'ewStatusText' );

			if ( 'string' === typeof status ) {
				status = status.replace( '{counter}', counter );
			}

			return `<span class="status">${ status } (${ percent }%)</span>`;
		}

		constructor( options ) {
			super( options );

			this.status = this.controller.state().get( 'ewStatus' );
			this.status.on( 'change', this.update, this );

			this.controller.on( 'watermarking:activate watermarking:deactivate', this.toggleVisibility, this );
			this.controller.on( 'restoring:activate restoring:deactivate', this.toggleVisibility, this );
		}

		render() {
			this.toggleVisibility();

			return this;
		}

		toggleVisibility() {
			if ( this.controller.isModeActive( 'watermarking' ) || this.controller.isModeActive( 'restoring' ) ) {
				this.$el.removeClass( 'hidden' );
			} else {
				this.$el.addClass( 'hidden' );
			}
		}

		update() {
			const
				processed = this.status.get( 'processed' ),
				total = this.status.get( 'total' );

			this.$el.html( this.template( processed, total ) );
		}

		cancel( e ) {
			e.preventDefault();
			this.controller.deactivateMode( 'watermarking' );
		}
	}

	StatusExport = Status;
}

export default StatusExport;
