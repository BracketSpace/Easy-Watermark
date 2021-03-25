/**
 * Get editor state
 *
 * @param  {Object} state      State object.
 * @param  {string|null} [key=null] State param key (optional).
 * @return {mixed}            State object or param.
 */
export function getEditorState( state, key = null ) {
	if ( null !== key && undefined !== state.editorState[ key ] ) {
		return state.editorState[ key ];
	}

	return state.editorState;
}

/**
 * Get position X
 *
 * @param  {Object} state State object.
 * @return {number}       Position X.
 */
export function getEditorPositionX( state ) {
	return getEditorState( state, 'positionX' );
}

/**
 * Get position Y
 *
 * @param  {Object} state State object.
 * @return {number}       Position Y.
 */
export function getEditorPositionY( state ) {
	return getEditorState( state, 'positionY' );
}

/**
 * Get editor scale
 *
 * @param  {Object} state State object.
 * @return {number}       Scale.
 */
export function getEditorScale( state ) {
	return getEditorState( state, 'scale' );
}

/**
 * Get preview image ID
 *
 * @param  {Object} state State object.
 * @return {number}       Preview image ID.
 */
export function getEditorPreviewImageID( state ) {
	return getEditorState( state, 'previewImageID' );
}

/**
 * Get preview image size.
 *
 * @param  {Object} state State object.
 * @return {number}       Preview image size..
 */
export function getEditorPreviewImageSize( state ) {
	return getEditorState( state, 'previewImageSize' );
}

/**
 * Get editor position.
 *
 * @param  {Object} state State object.
 * @return {Object}       Editor position (x, y).
 */
export function getEditorPosition( state ) {
	return {
		x: getEditorPositionX( state ),
		y: getEditorPositionY( state ),
	};
}
