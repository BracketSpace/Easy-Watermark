import $ from 'jquery'

export default function ( content, type = 'info' ) {

	let notice = $( document.createElement( 'div' ) ),
			p      = $( document.createElement( 'p' ) ),
			button = $( document.createElement( 'button' ) )

	notice.addClass( 'notice notice-' + type + ' is-dismissible' ).hide()
	button.addClass( 'notice-dismiss' )
	p.html( content )

	notice.append( p ).append( button )

	button.on( 'click', ( e ) => {
		e.preventDefault()
		notice.fadeOut( 200, () => {
			notice.remove()
		} )
	} )

	$( 'hr.wp-header-end' ).after( notice )

	notice.fadeIn( 200 )

}
