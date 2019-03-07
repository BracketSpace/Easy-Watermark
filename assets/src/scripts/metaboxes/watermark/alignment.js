import $ from 'jquery'

export default class {
	constructor() {
		this.metabox = $( '#alignment' )
	}

	enable( type ) {
		this.metabox.fadeIn( 200 )
	}
}
