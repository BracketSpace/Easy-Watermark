/**
 * External dependencies
 */
import { isEmpty, assign, merge } from 'lodash';

/**
 * Internal dependencies
 */
import {
	TEdits,
	TSelectable,
	TStoreState,
	TWatermark,
	TWatermarkConfig,
	TWatermarkObject,
	TWatermarkObjects,
	TWatermarkValues,
} from 'types';

/**
 * Get edited data
 *
 * @param  {Object} state State object.
 * @return {Object}       Edited data.
 */
export function getEditedData( state: TStoreState ) : TWatermark {
	const edits: TEdits = [ ...state.edits.past ];

	if ( ! isEmpty( state.edits.current ) ) {
		edits.push( state.edits.current );
	}

	const editedData = assign( {}, state.initialData, state.editedData );

	editedData.config = assign(
		{},
		state.initialData.config,
		state.editedData.config
	);

	editedData.objects = merge(
		{},
		state.initialData.objects,
		...state.edits.past,
		state.edits.current
	);

	return editedData;
}

/**
 * Get watermark attribute
 *
 * @param  state State object.
 * @param  key   Attribute key.
 * @return       Attribute value.
 */
export function getAttribute( state: TStoreState, key: string ) : TWatermarkValues {
	const data = getEditedData( state );

	if ( 'title' === key && 'auto-draft' === data.status ) {
		return '';
	}

	return data[ key ];
}

/**
 * Get watermark config
 *
 * @param  state       State object.
 * @param  [key=false] Config item key (optional).
 * @return             Config value.
 */
export function getConfig( state: TStoreState, key?: string ) {
	const data = getEditedData( state );

	if ( ! data.config ) {
		return;
	}

	if ( key ) {
		return data.config[ key as keyof TWatermarkConfig ];
	}

	return data.config;
}

/**
 * Get watermark objects (text and image)
 *
 * @param  state State object.
 * @return       Watermark objects.
 */
export function getObjects( state: TStoreState ) : TWatermarkObjects {
	const objects: TWatermarkObjects = getEditedData( state ).objects || [];

	for ( const [ id, object ] of Object.entries( objects ) ) {
		object.isActive = parseInt( id ) === state.activeObject;
	}

	return objects;
}

/**
 * Get single watermark object
 *
 * @param  state State object.
 * @param  id    Object id.
 * @return       Watermark object.
 */
export function getObject( state: TStoreState, id: number ) : TWatermarkObject{
	const object = getEditedData( state ).objects[ id ];

	if ( object ) {
		object.isActive = id === state.activeObject;
	}

	return object;
}

/**
 * Get active (currently focused in editor) watermark object
 *
 * @param  state State object.
 * @return       Active watermark object.
 */
export function getActiveObject( state: TStoreState ) : number | undefined {
	return state.activeObject;
}

/**
 * Get image sizes configuration for edited watermark
 *
 * @param  {Object} state State object.
 * @return {Object}       Selected image sizes.
 */
export function getImageSizes( state: TStoreState ) : TSelectable {
	const data = getEditedData( state );
	const sizes: TSelectable = [];

	if ( ! isEmpty( state.imageSizes ) ) {
		for ( const key in state.imageSizes ) {
			const selected =
				data.config &&
				data.config.image_sizes &&
				data.config.image_sizes.includes( key );

			sizes[ key ] = {
				label: state.imageSizes[ key ],
				selected,
			};
		}
	}

	return sizes;
}

/**
 * Get image types configuration for edited watermark
 *
 * @param  {Object} state State object.
 * @return {Object}       Selected image types.
 */
export function getImageTypes( state: TStoreState ) : TSelectable {
	const data = getEditedData( state );
	const types: TSelectable = [];

	if ( ! isEmpty( state.mimeTypes ) ) {
		for ( const key in state.mimeTypes ) {
			const selected =
				data.config &&
				data.config.image_types &&
				data.config.image_types.includes( key );

			types[ key ] = {
				label: state.mimeTypes[ key ],
				selected,
			};
		}
	}

	return types;
}

/**
 * Get post types configuration for edited watermark
 *
 * @param  {Object} state State object.
 * @return {Object}       Selected post types.
 */
export function getPostTypes( state: TStoreState ) : TSelectable {
	const data = getEditedData( state );
	const types: TSelectable = [];

	if ( ! isEmpty( state.postTypes ) ) {
		for ( const key in state.postTypes ) {
			const selected =
				data.config &&
				data.config.post_types &&
				data.config.post_types.includes( key );

			types[ key ] = {
				label: state.postTypes[ key ].label,
				selected,
			};
		}
	}

	return types;
}
