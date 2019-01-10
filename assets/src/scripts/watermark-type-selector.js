import $ from 'jquery'

import contentMetabox from './metaboxes/content.js'
import alignmentMetabox from './metaboxes/alignment.js'

export default class WatermarkTypeSelector {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this )

		this.selector = $( 'input.watermark-type' )

		this.metaboxes = [
			new contentMetabox(),
			new alignmentMetabox()
		]

		const selected = this.selector.filter('[checked]')

		if ( selected.length ) {
			this.selectWatermarkType( selected[0].value )
		}

		this.selector.on( 'change', ( e ) => {
			this.selectWatermarkType( e.target.value )
		} )
	}

	selectWatermarkType( type ) {
		for ( let metabox of this.metaboxes ) {
			metabox.enable( type )
		}
	}

	enableTextWatermark() {
		console.log( 'enable text' )
	}

	enableImageWatermark() {
		console.log( 'enable image' )
	}
}
