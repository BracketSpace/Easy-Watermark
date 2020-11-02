/**
 * Internal dependencies
 */
import {
	SET_EDITOR_STATE,
	SET_EDITOR_POSITION,
	SET_EDITOR_POSITION_SCALE,
	SET_EDITOR_SCALE,
	SET_EDITOR_PREVIEW_IMAGE,
} from '../action-types';

/**
 * @param key
 * @param value
 */
export function setEditorState( key, value ) {
	return {
		type: SET_EDITOR_STATE,
		key,
		value,
	};
}

/**
 * @param position
 */
export function setEditorPosition( position ) {
	return {
		type: SET_EDITOR_POSITION,
		position,
	};
}

/**
 *
 */
export function setEditorPositionScale( { x, y, scale } ) {
	return {
		type: SET_EDITOR_POSITION_SCALE,
		position: { x, y },
		scale,
	};
}

/**
 * @param scale
 */
export function setEditorScale( scale ) {
	return {
		type: SET_EDITOR_SCALE,
		scale,
	};
}

/**
 * @param attachment
 */
export function setEditorPreviewImage( attachment ) {
	return {
		type: SET_EDITOR_PREVIEW_IMAGE,
		attachment,
	};
}
