import $ from 'jquery'

export default class {
	constructor() {
		this.metabox    = $( '#text-options' )
		this.colorInput = this.metabox.find( '#text-color' )
		this.fields     = this.metabox.find( 'input, select' )

		this.colorInput.wpColorPicker( {
			palettes: true,
		} )
	}

	enable( type ) {
		if ( type == 'text' ) {
			this.metabox.fadeIn( 200 )
			this.fields.prop( 'disabled', false )
		} else {
			this.metabox.hide()
			this.fields.prop( 'disabled', true )
		}
	}
}
