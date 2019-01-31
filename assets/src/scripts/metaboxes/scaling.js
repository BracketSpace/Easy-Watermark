import $ from 'jquery'

export default class {
	constructor() {
		this.metabox           = $( '#scaling' )
		this.scalingModeSelect = this.metabox.find( '#watermark-scaling-mode' )
		this.hiddenSections    = this.metabox.find( '.hidden' )

		this.toggleOptionsVisibility = this.toggleOptionsVisibility.bind( this )

		this.scalingModeSelect.on( 'change', this.toggleOptionsVisibility )

		this.toggleOptionsVisibility();
	}

	enable( type ) {
		if ( type == 'image' ) {
			this.metabox.fadeIn( 200 )
		} else {
			this.metabox.hide()
		}
	}

	toggleOptionsVisibility() {
		switch ( this.scalingModeSelect.val() ) {
			case 'fit_to_width' :
			case 'fit_to_height' :
				this.hiddenSections.show()
				break
			default :
				this.hiddenSections.hide()
				break
		}
	}
}
