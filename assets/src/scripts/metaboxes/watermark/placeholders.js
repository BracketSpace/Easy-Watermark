import $ from 'jquery'
import Clipboard from 'clipboard'

export default class {
	constructor() {
		this.metabox   = $( '#placeholders' )
		this.codeItems = this.metabox.find( 'code' )

		this.clipboard = new Clipboard( '.placeholders-list code' )

		this.clipboardSuccess = this.clipboardSuccess.bind( this )

		this.clipboard.on( 'success', this.clipboardSuccess )
	}

	enable( type ) {
		this.metabox.fadeIn( 200 )
	}

	clipboardSuccess( e ) {
		let item = $( e.trigger )

		item.text( 'Copied' )

		setTimeout( () => {
			item.text( e.text )
		}, 1000 )
	}
}
