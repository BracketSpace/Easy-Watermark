/**
 * External dependencies
 */
import { isEmpty, assign, merge } from 'lodash';

/**
 * @param state
 */
export function getEditedData( state ) {
	const edits = [
		...state.edits.past,
	];

	if ( ! isEmpty( state.edits.current ) ) {
		edits.push( state.edits.current );
	}

	const editedData = assign( {}, state.initialData, state.editedData );

	editedData.config = assign( {}, state.initialData.config, state.editedData.config );
	editedData.objects = merge( {}, state.initialData.objects, ...state.edits.past, state.edits.current );

	return editedData;
}

/**
 * @param state
 * @param key
 */
export function getAttribute( state, key ) {
	const data = getEditedData( state );

	if ( 'title' === key && 'auto-draft' === data.status ) {
		return '';
	}

	return data[ key ];
}

/**
 * @param state
 * @param key
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
 * @param state
 */
export function getObjects( state ) {
	const objects = getEditedData( state ).objects || {};

	for ( const [ id, object ] of Object.entries( objects ) ) {
		object.isActive = ( id === state.activeObject );
	}

	return objects;
}

/**
 * @param state
 * @param id
 */
export function getObject( state, id ) {
	const object = getEditedData( state ).objects[ id ];

	if ( object ) {
		object.isActive = ( id === state.activeObject );
	}

	return object;
}

/**
 * @param state
 */
export function getActiveObject( state ) {
	return state.activeObject;
}

/**
 * @param state
 */
export function getImageSizes( state ) {
	const data = getEditedData( state );
	const sizes = {};

	if ( ! isEmpty( state.imageSizes ) ) {
		for ( const key in state.imageSizes ) {
			const selected = data.config && data.config.image_sizes && data.config.image_sizes.includes( key );

			sizes[ key ] = {
				label: state.imageSizes[ key ],
				selected,
			};
		}
	}

	return sizes;
}

/**
 * @param state
 */
export function getImageTypes( state ) {
	const data = getEditedData( state );
	const types = {};

	if ( ! isEmpty( state.mimeTypes ) ) {
		for ( const key in state.mimeTypes ) {
			const selected = data.config && data.config.image_types && data.config.image_types.includes( key );

			types[ key ] = {
				label: state.mimeTypes[ key ],
				selected,
			};
		}
	}

	return types;
}

/**
 * @param state
 */
export function getPostTypes( state ) {
	const data = getEditedData( state );
	const types = {};

	if ( ! isEmpty( state.postTypes ) ) {
		for ( const key in state.postTypes ) {
			const selected = data.config && data.config.post_types && data.config.post_types.includes( key );

			types[ key ] = {
				label: state.postTypes[ key ].label,
				selected,
			};
		}
	}

	return types;
}
