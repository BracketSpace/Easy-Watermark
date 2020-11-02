/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * @param content
 * @param type
 */
export function addNotice( content, type = 'info' ) {
	const notice = $( document.createElement( 'div' ) ),
		p = $( document.createElement( 'p' ) ),
		button = $( document.createElement( 'button' ) );

	notice.addClass( 'notice notice-' + type + ' is-dismissible' ).hide();
	button.addClass( 'notice-dismiss' );
	p.html( content );

	notice.append( p ).append( button );

	button.on( 'click', ( e ) => {
		e.preventDefault();
		notice.fadeOut( 200, () => {
			notice.remove();
		} );
	} );

	$( 'hr.wp-header-end' ).after( notice );

	notice.fadeIn( 200 );
}

/**
 * @param selection
 * @param backup
 * @param remove
 */
export function filterSelection( selection, backup = false, remove = true ) {
	let length = selection.length;

	for ( const model of selection.clone().models ) { // eslint-disable-line no-unused-vars
		if ( ! isImage( model ) || model.get( 'usedAsWatermark' ) ||
			( true === backup && ! model.get( 'hasBackup' ) ) ) {
			if ( true === remove ) {
				selection.remove( model );
			}

			length--;
		}
	}

	return length;
}

/**
 * @param url
 * @param version
 */
export function imageVersion( url, version ) {
	const	index = url.indexOf( '?' );

	if ( -1 !== index ) {
		url = url.substr( 0, index );
	}

	url += '?v=' + version;

	return url;
}

/**
 * @param mime
 */
export function isImage( mime ) {
	if ( 'object' === typeof mime && mime.get ) {
		// It's a model.
		mime = mime.get( 'mime' );
	}

	return Object.keys( ew.mime ).includes( mime );
}
