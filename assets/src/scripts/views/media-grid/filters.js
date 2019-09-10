/* global wp */

if ( wp.media ) {
	if ( 'function' === typeof wp.media.view.AttachmentFilters.All ) {
		wp.media.view.AttachmentFilters.All = class extends wp.media.view.AttachmentFilters.All {
			initialize() {
				super.initialize();

				this.controller.on( 'processing:activate processing:deactivate', this.toggleDisabled, this );
			}

			toggleDisabled() {
				this.$el.prop( 'disabled', ! this.$el.is( ':disabled' ) );
			}
		};
	}

	if ( 'function' === typeof wp.media.view.AttachmentFilters.Uploaded ) {
		wp.media.view.AttachmentFilters.Uploaded = class extends wp.media.view.AttachmentFilters.Uploaded {
			initialize() {
				super.initialize();

				this.controller.on( 'processing:activate processing:deactivate', this.toggleDisabled, this );
			}

			toggleDisabled() {
				this.$el.prop( 'disabled', ! this.$el.is( ':disabled' ) );
			}
		};
	}

	if ( 'function' === typeof wp.media.view.DateFilter ) {
		wp.media.view.DateFilter = class extends wp.media.view.DateFilter {
			initialize() {
				super.initialize();

				this.controller.on( 'processing:activate processing:deactivate', this.toggleDisabled, this );
			}

			toggleDisabled() {
				this.$el.prop( 'disabled', ! this.$el.is( ':disabled' ) );
			}
		};
	}
}
