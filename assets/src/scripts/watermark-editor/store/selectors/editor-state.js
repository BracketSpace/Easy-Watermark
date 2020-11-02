/**
 * @param state
 * @param key
 */
export function getEditorState( state, key = null ) {
	if ( null !== key && undefined !== state.editorState[ key ] ) {
		return state.editorState[ key ];
	}

	return state.editorState;
}

/**
 * @param state
 */
export function getEditorPositionX( state ) {
	return getEditorState( state, 'positionX' );
}

/**
 * @param state
 */
export function getEditorPositionY( state ) {
	return getEditorState( state, 'positionY' );
}

/**
 * @param state
 */
export function getEditorScale( state ) {
	return getEditorState( state, 'scale' );
}

/**
 * @param state
 */
export function getEditorPreviewImageID( state ) {
	return getEditorState( state, 'previewImageID' );
}

/**
 * @param state
 */
export function getEditorPreviewImageSize( state ) {
	return getEditorState( state, 'previewImageSize' );
}

/**
 * @param state
 */
export function getEditorPosition( state ) {
	return {
		x: getEditorPositionX( state ),
		y: getEditorPositionY( state ),
	};
}
