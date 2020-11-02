/**
 * External dependencies
 */
import { uniqueId } from 'lodash';

/**
 * Internal dependencies
 */
import {
	LOAD_WATERMARK_SUCCESS,
	EDIT_WATERMARK_ATTRIBUTE,
	EDIT_WATERMARK_CONFIG,
	SET_IMAGE_SIZES,
	SET_IMAGE_TYPES,
	SET_POST_TYPES,
	SET_ACTIVE_OBJECT,
} from '../action-types';

/**
 * @param state
 * @param action
 */
export function initialData( state, action ) {
	if ( LOAD_WATERMARK_SUCCESS === action.type ) {
		if ( action.initial ) {
			const objects = {};

			for ( const key in action.watermark.objects ) {
				const object = action.watermark.objects[ key ];
				object.id = uniqueId( 'object_' );
				objects[ object.id ] = object;
			}

			action.watermark.objects = objects;
		}

		const result = Object.keys( action.watermark )
			.filter( ( key ) => [ 'id', 'status', 'config', 'objects' ].includes( key ) )
			.reduce( ( accumulator, key ) => {
				return {
					...accumulator,
					[ key ]: action.watermark[ key ],
				};
			}, {
				title: action.watermark.title.raw,
			} );

		return result;
	}

	return state;
}

/**
 * @param state
 * @param action
 */
export function editedData( state, action ) {
	switch ( action.type ) {
		case LOAD_WATERMARK_SUCCESS :
			return {};
		case EDIT_WATERMARK_ATTRIBUTE :
			return {
				...state,
				[ action.key ]: action.value,
			};
		case EDIT_WATERMARK_CONFIG :
			return {
				...state,
				config: {
					...action.config,
				},
			};
		case SET_IMAGE_SIZES :
			const sizes = [];

			for ( const key in action.imageSizes ) {
				if ( action.imageSizes[ key ].selected ) {
					sizes.push( key );
				}
			}

			return {
				...state,
				config: {
					...state.config,
					image_sizes: sizes,
				},
			};
		case SET_IMAGE_TYPES :
			const selectedImageTypes = [];

			for ( const key in action.imageTypes ) {
				if ( action.imageTypes[ key ].selected ) {
					selectedImageTypes.push( key );
				}
			}

			return {
				...state,
				config: {
					...state.config,
					image_types: selectedImageTypes,
				},
			};
		case SET_POST_TYPES :
			const selectedPostTypes = [];

			for ( const key in action.postTypes ) {
				if ( action.postTypes[ key ].selected ) {
					selectedPostTypes.push( key );
				}
			}

			return {
				...state,
				config: {
					...state.config,
					post_types: selectedPostTypes,
				},
			};
	}

	return state;
}

/**
 * @param state
 */
export function imageSizes( state ) {
	return state;
}

/**
 * @param state
 */
export function mimeTypes( state ) {
	return state;
}

/**
 * @param state
 */
export function postTypes( state ) {
	return state;
}

/**
 * @param state
 * @param action
 */
export function activeObject( state, action ) {
	if ( SET_ACTIVE_OBJECT === action.type ) {
		return action.id;
	}

	return state;
}
