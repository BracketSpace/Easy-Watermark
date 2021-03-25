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
 * Set editor state action.
 *
 * @param {string} key   Editor state key.
 * @param {mixed}  value Editor state value.
 * @return {Object}      Action object.
 */
export function setEditorState( key, value ) {
	return {
		type: SET_EDITOR_STATE,
		key,
		value,
	};
}

/**
 * Set editor position action.
 *
 * @param {Object} position Editor postion { x: number, y: number}.
 * @return {Object}         Action object.
 */
export function setEditorPosition( position ) {
	return {
		type: SET_EDITOR_POSITION,
		position,
	};
}

/**
 * Set editor position action.
 *
 * @param {Object} params       Editor postion and scale.
 * @param {number} params.x     Editor X position.
 * @param {number} params.y     Editor Y position.
 * @param {number} params.scale Editor Scale.
 * @return {Object}             Action object.
 */
export function setEditorPositionScale( { x, y, scale } ) {
	return {
		type: SET_EDITOR_POSITION_SCALE,
		position: { x, y },
		scale,
	};
}

/**
 * Set editor scale action.
 *
 * @param {number} scale Editor scale.
 * @return {Object}      Action object.
 */
export function setEditorScale( scale ) {
	return {
		type: SET_EDITOR_SCALE,
		scale,
	};
}

/**
 * Set editor preview image action.
 *
 * @param {Object} attachment Attachment selected as editor image.
 * @return {Object}           Action object.
 */
export function setEditorPreviewImage( attachment ) {
	return {
		type: SET_EDITOR_PREVIEW_IMAGE,
		attachment,
	};
}
