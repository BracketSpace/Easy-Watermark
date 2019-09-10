/**
 * External dependencies
 */
import $ from 'jquery';

export default class {
	constructor() {
		this.metabox = $( '#alignment' );
	}

	enable() {
		this.metabox.fadeIn( 200 );
	}
}
