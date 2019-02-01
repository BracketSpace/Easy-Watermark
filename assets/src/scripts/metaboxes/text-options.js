import $ from 'jquery'

export default class {
	constructor() {
		this.metabox = $( '#text-options' )
		this.colorInput = this.metabox.find( '#text-color' )

		this.colorInput.wpColorPicker( {
			palettes: true,
		} )
	}

	enable( type ) {
		if ( type == 'text' ) {
			this.metabox.fadeIn( 200 )
		} else {
			this.metabox.hide()
		}
	}
}
