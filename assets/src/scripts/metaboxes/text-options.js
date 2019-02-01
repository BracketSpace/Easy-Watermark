import $ from 'jquery'

export default class {
	constructor() {
		this.metabox = $( '#text-options' )
		this.colorInput = this.metabox.find( '#text-color' )

		this.colorInput.iris( {
			palettes: true,
			hide    : false,
			border  : false,
			mode    : 'hsv',
			change  : ( e, ui ) => {
				this.colorInputStyle( ui.color )
			}
		} )

		let color = this.colorInput.wpColorPicker( 'color', true )
		this.colorInputStyle( color )
	}

	colorInputStyle( color ) {
		this.colorInput.css( 'background-color', color.toString() );

		if ( color.l() < 50 ) {
			this.colorInput.css( 'color', '#fff' );
		} else {
			this.colorInput.css( 'color', '#000' );
		}
	}

	enable( type ) {
		if ( type == 'text' ) {
			this.metabox.fadeIn( 200 )
		} else {
			this.metabox.hide()
		}
	}
}
