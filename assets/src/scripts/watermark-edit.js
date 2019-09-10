/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Internal dependencies
 */
import '../styles/watermark-edit.scss';

import ContentMetabox from './metaboxes/watermark/content';
import AlignmentMetabox from './metaboxes/watermark/alignment';
import ApplyingRules from './metaboxes/watermark/applying-rules';
import Scaling from './metaboxes/watermark/scaling';
import TextOptions from './metaboxes/watermark/text-options';
import Preview from './metaboxes/watermark/preview';
import Placeholders from './metaboxes/watermark/placeholders';

import FormFields from './utils/form-fields';

/* global ew, ajaxurl */

class WatermarkEdit {
	constructor() {
		this.selectWatermarkType = this.selectWatermarkType.bind( this );
		this.triggerSave = this.triggerSave.bind( this );

		this.form = $( 'form#post' );
		this.selector = this.form.find( 'input.watermark-type' );

		this.metaboxes = [
			new ContentMetabox,
			new AlignmentMetabox,
			new ApplyingRules,
			new Scaling,
			new TextOptions,
			new Preview,
			new Placeholders,
		];

		new FormFields;

		const selected = this.selector.filter( '[checked]' );

		if ( selected.length ) {
			this.selectWatermarkType( selected[ 0 ].value );
		}

		this.selector.on( 'change', ( e ) => {
			this.selectWatermarkType( e.target.value );
		} );

		this.form
			.on( 'change', 'input, select', this.triggerSave )
			.on( 'ew.save', this.triggerSave );
	}

	selectWatermarkType( type ) {
		for ( const metabox of this.metaboxes ) { // eslint-disable-line no-unused-vars
			metabox.enable( type );
		}
	}

	triggerSave() {
		const params = {
			action: 'easy-watermark/autosave',
			nonce: ew.autosaveNonce,
		};

		let data = this.form.find( '[name^=watermark], [name=post_ID]' ).serialize();

		for ( const key in params ) { // eslint-disable-line no-unused-vars
			data += '&' + encodeURIComponent( key ) + '=' + encodeURIComponent( params[ key ] );
		}

		$.ajax( {
			type: 'post',
			url: ajaxurl,
			data,
		} ).done( ( response ) => {
			if ( true === response.success ) {
				this.form.trigger( 'ew.update' );
			}
		} ).fail( () => {
			// TODO: handle errors.
		} );
	}
}

$( document ).ready( () => new WatermarkEdit );
