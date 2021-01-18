/**
 * External dependencies
 */
import $ from 'jquery';

/* global ew */

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

export function imageVersion( url ) {
	const	index = url.indexOf( '?' );
	const version = `t=${ Date.now() }`;

	let query;

	if ( -1 !== index ) {
		query = url.substr( index );
		url = url.substr( 0, index );

		const regex = /([^\s])t=[0-9]+/;

		if ( query.match( regex ) ) {
			query = query.replace( regex, `$1${ version }` );
		} else {
			query += `&${ version }`;
		}
	} else {
		query = `?${ version }`;
	}

	return url + query;
}

export function isImage( mime ) {
	if ( 'object' === typeof mime && mime.get ) {
		// It's a model.
		mime = mime.get( 'mime' );
	}

	return Object.keys( ew.mime ).includes( mime );
}
