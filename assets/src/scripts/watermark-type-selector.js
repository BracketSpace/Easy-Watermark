import $ from 'jquery'

import contentMetabox from './metaboxes/content.js'

export default class WatermarkTypeSelector {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this )

		this.metaboxes = [
			new contentMetabox()
		]

		$( document ).on( 'change', 'input.watermark-type', this.selectWatermarkType )
	}

	selectWatermarkType( e ) {

		for ( let metabox of this.metaboxes ) {
			metabox.enable( e.target.value )
		}
		// switch ( e.target.value ) {
		// 	case 'text':
		// 		this.enableTextWatermark()
		// 		break
		// 	case 'image':
		// 		this.enableImageWatermark()
		// 		break
		// }
	}

	enableTextWatermark() {
		console.log( 'enable text' )
	}

	enableImageWatermark() {
		console.log( 'enable image' )
	}
}
