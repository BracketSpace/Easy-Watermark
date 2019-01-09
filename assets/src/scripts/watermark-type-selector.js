import $ from 'jquery'

import contentMetabox from './metaboxes/content.js'
import alignmentMetabox from './metaboxes/alignment.js'

export default class WatermarkTypeSelector {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this )

		this.metaboxes = [
			new contentMetabox(),
			new alignmentMetabox()
		]

		$( document ).on( 'change', 'input.watermark-type', this.selectWatermarkType )
	}

	selectWatermarkType( e ) {

		for ( let metabox of this.metaboxes ) {
			metabox.enable( e.target.value )
		}
	}

	enableTextWatermark() {
		console.log( 'enable text' )
	}

	enableImageWatermark() {
		console.log( 'enable image' )
	}
}
