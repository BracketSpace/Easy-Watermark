/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * Add WordPress admin notice
 *
 * @param {string} content       Notice content.
 * @param {string} [type='info'] Notice type.
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
 * Filter attachments selection. Used in media library to remove attachemtn used
 * as watermark from user-selected attachments before applying watermarks and to
 * remove attachments without backup available while restoring backups.
 *
 * @param  {Backbone.Collection}  selection      Selected attachments.
 * @param  {boolean}              [backup=false] Whether to filter files for
 *                                               backup restoration.
 * @param  {boolean}              [remove=true]  If true (default), items will
 *                                               be removed from the actual
 *                                               collection. If false, this
 *                                               function will just return the
 *                                               lenght of the cloned and
 *                                               filtered collection.
 * @return {number}                              Filtered collection length.
 */
export function filterSelection( selection, backup = false, remove = true ) {
	let length = selection.length;

	for ( const model of selection.clone().models ) {
		if (
			! isImage( model ) ||
			model.get( 'usedAsWatermark' ) ||
			( true === backup && ! model.get( 'hasBackup' ) )
		) {
			if ( true === remove ) {
				selection.remove( model );
			}

			length--;
		}
	}

	return length;
}

/**
 * Add image version (timestamp) param to image url. This is needed to force the
 * browser to refresh the image after it was watermarked or restored.
 *
 * @param  {string} url Image url.
 * @return {string}     Url with time param.
 */
export function imageVersion( url ) {
	const index = url.indexOf( '?' );
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

/**
 * Check if the given mime type is for an image.
 *
 * @param  {string}  mime MIME type.
 * @return {boolean}      If this is an image type.
 */
export function isImage( mime ) {
	if ( 'object' === typeof mime && mime.get ) {
		// It's a model.
		mime = mime.get( 'mime' );
	}

	return Object.keys( ew.mime ).includes( mime );
}
