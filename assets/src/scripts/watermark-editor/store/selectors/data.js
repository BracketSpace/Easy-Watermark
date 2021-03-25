/**
 * External dependencies
 */
import { isEmpty, assign, merge } from 'lodash';

/**
 * Get edited data
 *
 * @param  {Object} state State object.
 * @return {Object}       Edited data.
 */
export function getEditedData( state ) {
	const edits = [ ...state.edits.past ];

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
 * @param  {Object} state State object.
 * @param  {string} key   Attribute key.
 * @return {mixed}        Attribute value.
 */
export function getAttribute( state, key ) {
	const data = getEditedData( state );

	if ( 'title' === key && 'auto-draft' === data.status ) {
		return '';
	}

	return data[ key ];
}

/**
 * Get watermark config
 *
 * @param  {Object}  state            State object.
 * @param  {string|false} [key=false] Config item key (optional).
 * @return {mixed}                    Config value.
 */
export function getConfig( state, key = false ) {
	const data = getEditedData( state );

	if ( ! data.config ) {
		return;
	}

	if ( key ) {
		return data.config[ key ];
	}

	return data.config;
}

/**
 * Get watermark objects (text and image)
 *
 * @param  {Object} state State object.
 * @return {Array}        Watermark objects.
 */
export function getObjects( state ) {
	const objects = getEditedData( state ).objects || {};

	for ( const [ id, object ] of Object.entries( objects ) ) {
		object.isActive = id === state.activeObject;
	}

	return objects;
}

/**
 * Get single watermark object
 *
 * @param  {Object} state State object.
 * @param  {number} id    Object id.
 * @return {Object}       Watermark object.
 */
export function getObject( state, id ) {
	const object = getEditedData( state ).objects[ id ];

	if ( object ) {
		object.isActive = id === state.activeObject;
	}

	return object;
}

/**
 * Get active (currently focused in editor) watermark object
 *
 * @param  {Object} state State object.
 * @return {Object}       Active watermark object.
 */
export function getActiveObject( state ) {
	return state.activeObject;
}

/**
 * Get image sizes configuration for edited watermark
 *
 * @param  {Object} state State object.
 * @return {Object}       Selected image sizes.
 */
export function getImageSizes( state ) {
	const data = getEditedData( state );
	const sizes = {};

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
export function getImageTypes( state ) {
	const data = getEditedData( state );
	const types = {};

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
export function getPostTypes( state ) {
	const data = getEditedData( state );
	const types = {};

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
