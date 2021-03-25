/**
 * Internal dependencies
 */
import {
	SET_EDITOR_STATE,
	SET_EDITOR_POSITION,
	SET_EDITOR_POSITION_SCALE,
	LOAD_EDITOR_SETTINGS,
	SET_EDITOR_PREVIEW_IMAGE,
	SET_EDITOR_SCALE,
} from '../action-types';

/**
 * Reducer for editor state actions.
 *
 * @param  {mixed}  state  State.
 * @param  {Object} action Action object.
 * @return {mixed}         Reduced state.
 */
export function editorState( state, action ) {
	if ( SET_EDITOR_STATE === action.type ) {
		return {
			...state,
			[ action.key ]: action.value,
		};
	}

	if ( SET_EDITOR_POSITION === action.type ) {
		const newState = { ...state };

		if ( undefined !== action.position.x ) {
			newState.positionX = action.position.x;
		}

		if ( undefined !== action.position.y ) {
			newState.positionY = action.position.y;
		}

		return newState;
	}

	if ( SET_EDITOR_POSITION_SCALE === action.type ) {
		return {
			...state,
			positionX: action.position.x,
			positionY: action.position.y,
			scale: action.scale,
		};
	}

	if ( SET_EDITOR_SCALE === action.type ) {
		return {
			...state,
			scale: action.scale,
		};
	}

	if ( SET_EDITOR_PREVIEW_IMAGE === action.type ) {
		return {
			...state,
			previewImageID: action.attachment.get( 'id' ),
			previewImage: action.attachment,
		};
	}

	if (
		LOAD_EDITOR_SETTINGS === action.type &&
		undefined !== action.settings.preview_image
	) {
		return {
			...state,
			previewImageID: action.settings.preview_image,
		};
	}

	return state;
}
