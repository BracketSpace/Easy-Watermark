/**
 * Internal dependencies
 */
import { TStoreState, TEditorState } from 'types';

type TEditorStateValue<T> =
	T extends keyof TEditorState ? TEditorState[T] :
	TEditorState;

/**
 * Get editor state
 *
 * @param  {Object} state      State object.
 * @param  {string|null} [key=null] State param key (optional).
 * @return {mixed}            State object or param.
 */
export function getEditorState<K extends keyof TEditorState>( state: TStoreState, key: K | null = null ) : TEditorStateValue<typeof key> {
	if ( null !== key && undefined !== state.editorState[ key as keyof TEditorState ] ) {
		return state.editorState[ key as keyof TEditorState ] as TEditorStateValue<typeof key>;
	}

	return state.editorState;
}

/**
 * Get position X
 *
 * @param  state State object.
 * @return       Position X.
 */
export function getEditorPositionX( state: TStoreState ) : number {
	return getEditorState( state, 'positionX' ) as number;
}

/**
 * Get position Y
 *
 * @param  state State object.
 * @return       Position Y.
 */
export function getEditorPositionY( state: TStoreState ) : number {
	return getEditorState( state, 'positionY' ) as number;
}

/**
 * Get editor scale
 *
 * @param  state State object.
 * @return       Scale.
 */
export function getEditorScale( state: TStoreState ) : number {
	return getEditorState( state, 'scale' ) as number;
}

/**
 * Get preview image ID
 *
 * @param  state State object.
 * @return       Preview image ID.
 */
export function getEditorPreviewImageID( state: TStoreState ) : number {
	return getEditorState( state, 'previewImageID' ) as number;
}

/**
 * Get preview image size.
 *
 * @param  state State object.
 * @return       Preview image size..
 */
export function getEditorPreviewImageSize( state: TStoreState ) : string {
	return getEditorState( state, 'previewImageSize' ) as string;
}

/**
 * Get editor position.
 *
 * @param  state State object.
 * @return       Editor position (x, y).
 */
export function getEditorPosition( state: TStoreState ) : { x: number, y: number } {
	return {
		x: getEditorPositionX( state ),
		y: getEditorPositionY( state ),
	};
}
