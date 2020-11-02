/**
 * External dependencies
 */
import { uniqueId } from 'lodash';

/**
 * Internal dependencies
 */
import {
	LOAD_WATERMARK_DATA,
	LOAD_WATERMARK_SUCCESS,
	LOAD_WATERMARK_ERROR,
	EDIT_WATERMARK_ATTRIBUTE,
	EDIT_WATERMARK_CONFIG,
	SET_IMAGE_SIZES,
	SET_IMAGE_TYPES,
	SET_POST_TYPES,
	CREATE_OBJECT,
	EDIT_OBJECT,
	SET_ACTIVE_OBJECT,
} from '../action-types';

import {
	IMAGE_OBJECT_DEFAULTS,
	TEXT_OBJECT_DEFAULTS,
} from '../defaults';

/**
 * @param watermarkID
 */
export function loadWatermarkData( watermarkID ) {
	return {
		type: LOAD_WATERMARK_DATA,
		watermarkID,
	};
}

/**
 * @param watermark
 * @param initial
 */
export function loadWatermarkSuccess( watermark, initial = false ) {
	return {
		type: LOAD_WATERMARK_SUCCESS,
		watermark,
		initial,
	};
}

/**
 * @param error
 */
export function loadWatermarkError( error ) {
	return {
		type: LOAD_WATERMARK_ERROR,
		error,
	};
}

/**
 * @param key
 * @param value
 */
export function editAttribute( key, value ) {
	return {
		type: EDIT_WATERMARK_ATTRIBUTE,
		key,
		value,
	};
}

/**
 * @param key
 * @param value
 */
export function editConfig( key, value = null ) {
	const config = ( 'string' === typeof key ) ? { [ key ]: value } : key;

	return {
		type: EDIT_WATERMARK_CONFIG,
		config,
	};
}

/**
 * @param imageSizes
 */
export function setImageSizes( imageSizes ) {
	return {
		type: SET_IMAGE_SIZES,
		imageSizes,
	};
}

/**
 * @param imageTypes
 */
export function setImageTypes( imageTypes ) {
	return {
		type: SET_IMAGE_TYPES,
		imageTypes,
	};
}

/**
 * @param postTypes
 */
export function setPostTypes( postTypes ) {
	return {
		type: SET_POST_TYPES,
		postTypes,
	};
}

/**
 * @param object
 */
export function createObject( object ) {
	const defaults = 'image' === object.type ? IMAGE_OBJECT_DEFAULTS : TEXT_OBJECT_DEFAULTS;

	return {
		type: CREATE_OBJECT,
		object: {
			...defaults,
			...object,
			id: uniqueId( 'object_' ),
		},
	};
}

/**
 * @param id
 * @param key
 * @param value
 */
export function editObject( id, key, value = null ) {
	const data = ( 'string' === typeof key ) ? { [ key ]: value } : key;

	return {
		type: EDIT_OBJECT,
		id,
		data,
	};
}

/**
 * @param id
 */
export function setActiveObject( id ) {
	return {
		type: SET_ACTIVE_OBJECT,
		id,
	};
}
