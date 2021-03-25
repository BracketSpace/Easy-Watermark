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

import { IMAGE_OBJECT_DEFAULTS, TEXT_OBJECT_DEFAULTS } from '../defaults';

/**
 * Load watermark data action.
 *
 * @param  {number} watermarkID Watermark ID.
 * @return {Object}             Action object.
 */
export function loadWatermarkData( watermarkID ) {
	return {
		type: LOAD_WATERMARK_DATA,
		watermarkID,
	};
}

/**
 * Load watermark success action.
 *
 * @param  {Object}  watermark       Watermark object.
 * @param  {boolean} [initial=false] Flag if this is an initial load.
 * @return {Object}                  Action object.
 */
export function loadWatermarkSuccess( watermark, initial = false ) {
	return {
		type: LOAD_WATERMARK_SUCCESS,
		watermark,
		initial,
	};
}

/**
 * Load watermark error action.
 *
 * @param  {Object} error Error object.
 * @return {Object}       Action object.
 */
export function loadWatermarkError( error ) {
	return {
		type: LOAD_WATERMARK_ERROR,
		error,
	};
}

/**
 * Edit attribute action.
 *
 * @param  {string} key   Attribute key.
 * @param  {mixed} value  Attribute value.
 * @return {Object}       Action object.
 */
export function editAttribute( key, value ) {
	return {
		type: EDIT_WATERMARK_ATTRIBUTE,
		key,
		value,
	};
}

/**
 * Edit config key action.
 *
 * @param  {string} key          Config key.
 * @param  {mixed} [value=null]  Config value.
 * @return {Object}              Action object.
 */
export function editConfig( key, value = null ) {
	const config = 'string' === typeof key ? { [ key ]: value } : key;

	return {
		type: EDIT_WATERMARK_CONFIG,
		config,
	};
}

/**
 * Set image sizes action.
 *
 * @param {Array} imageSizes Selected mage sizes.
 * @return {Object}          Action object.
 */
export function setImageSizes( imageSizes ) {
	return {
		type: SET_IMAGE_SIZES,
		imageSizes,
	};
}

/**
 * Set image types action.
 *
 * @param {Array} imageTypes Selected image types.
 * @return {Object}          Action object.
 */
export function setImageTypes( imageTypes ) {
	return {
		type: SET_IMAGE_TYPES,
		imageTypes,
	};
}

/**
 * Set post types action.
 *
 * @param {Array} postTypes Selected post types.
 * @return {Object}         Action object.
 */
export function setPostTypes( postTypes ) {
	return {
		type: SET_POST_TYPES,
		postTypes,
	};
}

/**
 * Create watermark object action.
 *
 * @param  {Object} object Watermark object data.
 * @return {Object}        Action object.
 */
export function createObject( object ) {
	const defaults =
		'image' === object.type ? IMAGE_OBJECT_DEFAULTS : TEXT_OBJECT_DEFAULTS;

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
 * Edit watermark object action.
 *
 * @param  {number}       id           Object id.
 * @param  {string|Oject} key          Object attribute key or { key: value } object for multiple keys.
 * @param  {mixed}        [value=null] Attribute value if single string key passed as previous param.
 * @return {Object}                    Action object.
 */
export function editObject( id, key, value = null ) {
	const data = 'string' === typeof key ? { [ key ]: value } : key;

	return {
		type: EDIT_OBJECT,
		id,
		data,
	};
}

/**
 * Set active object action.
 *
 * @param {number} id Object id.
 * @return {Object}   Action object.
 */
export function setActiveObject( id ) {
	return {
		type: SET_ACTIVE_OBJECT,
		id,
	};
}
