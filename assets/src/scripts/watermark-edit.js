import $ from 'jquery'

import ContentMetabox from './metaboxes/watermark/content.js'
import AlignmentMetabox from './metaboxes/watermark/alignment.js'
import ApplyingRules from './metaboxes/watermark/applying-rules.js'
import Scaling from './metaboxes/watermark/scaling.js'
import TextOptions from './metaboxes/watermark/text-options.js'
import Preview from './metaboxes/watermark/preview.js'
import Placeholders from './metaboxes/watermark/placeholders.js'

class WatermarkEdit {
	constructor() {
		$( document ).ready( () => {
			this.init()
		} )
	}

	init() {
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
			new Preview(),
			new Placeholders(),
		]

		const selected = this.selector.filter('[checked]')

		if ( selected.length ) {
			this.selectWatermarkType( selected[0].value )
		}

		this.selector.on( 'change', ( e ) => {
			this.selectWatermarkType( e.target.value )
		} )

		this.form.on( 'change', 'input, select', this.triggerSave )
						 .on( 'ew.save', this.triggerSave )
	}

	selectWatermarkType( type ) {
		for ( let metabox of this.metaboxes ) {
			metabox.enable( type )
		}
	}

	triggerSave() {

		let params = {
			action: 'easy-watermark/autosave',
			nonce : ew.autosaveNonce
		}

		let data = this.form.find('[name^=watermark], [name=post_ID]').serialize()

		for ( let key in params ) {
			data += '&' + encodeURIComponent( key ) + '=' + encodeURIComponent( params[key] )
		}

		$.ajax( {
				type: "post",
				url : ajaxurl,
				data: data,
		} ).done( ( response ) => {
			if ( true === response.success ) {
				this.form.trigger( 'ew.update' )
			}
		} ).fail( () => {
			console.log( 'fail' )
		} )
	}
}

new WatermarkEdit()
