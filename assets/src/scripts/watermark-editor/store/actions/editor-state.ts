/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

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
import type { TAttachment, TPosition, TPositionScale } from 'types';

/**
 * Set editor state action.
 *
 * @param  key   Editor state key.
 * @param  value Editor state value.
 * @return       Action object.
 */
export function setEditorState( key: string, value: any ) : Action {
	return {
		type: SET_EDITOR_STATE,
		key,
		value,
	};
}

/**
 * Set editor position action.
 *
 * @param  position Editor postion { x: number, y: number}.
 * @return          Action object.
 */
export function setEditorPosition( position: TPosition ) : Action {
	return {
		type: SET_EDITOR_POSITION,
		position,
	};
}

/**
 * Set editor position action.
 *
 * @param  params       Editor postion and scale.
 * @param  params.x     Editor X position.
 * @param  params.y     Editor Y position.
 * @param  params.scale Editor Scale.
 * @return              Action object.
 */
export function setEditorPositionScale( { x, y, scale }: TPositionScale ) : Action {
	return {
		type: SET_EDITOR_POSITION_SCALE,
		position: { x, y },
		scale,
	};
}

/**
 * Set editor scale action.
 *
 * @param  scale Editor scale.
 * @return       Action object.
 */
export function setEditorScale( scale: number ) : Action {
	return {
		type: SET_EDITOR_SCALE,
		scale,
	};
}

/**
 * Set editor preview image action.
 *
 * @param  attachment Attachment model selected as editor image.
 * @return            Action object.
 */
export function setEditorPreviewImage( attachment: TAttachment ) : Action {
	return {
		type: SET_EDITOR_PREVIEW_IMAGE,
		attachment,
	};
}
