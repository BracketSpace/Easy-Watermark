import $ from 'jquery'

import ContentMetabox from './metaboxes/content.js'
import AlignmentMetabox from './metaboxes/alignment.js'
import ApplyingRules from './metaboxes/applying-rules.js'
import Scaling from './metaboxes/scaling.js'
import TextOptions from './metaboxes/text-options.js'

export default class WatermarkTypeSelector {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this )

		this.selector = $( 'input.watermark-type' )

		this.metaboxes = [
			new ContentMetabox(),
			new AlignmentMetabox(),
			new ApplyingRules(),
			new Scaling(),
			new TextOptions(),
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
