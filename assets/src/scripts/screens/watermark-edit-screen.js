import $ from 'jquery'

import ContentMetabox from '../metaboxes/watermark/content.js'
import AlignmentMetabox from '../metaboxes/watermark/alignment.js'
import ApplyingRules from '../metaboxes/watermark/applying-rules.js'
import Scaling from '../metaboxes/watermark/scaling.js'
import TextOptions from '../metaboxes/watermark/text-options.js'

export default class {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this )
		this.triggerSave         = this.triggerSave.bind( this )

		this.form     = $( 'form#post' )
		this.selector = this.form.find( 'input.watermark-type' )

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

		this.form.on( 'change', 'input, select', this.triggerSave )
	}

	selectWatermarkType( type ) {
		for ( let metabox of this.metaboxes ) {
			metabox.enable( type )
		}
	}

	triggerSave() {
		console.log( wp.autosave )
		wp.autosave.server.triggerSave()
		console.log( 'saved' )
	}
}
