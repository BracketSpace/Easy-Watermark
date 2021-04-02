/**
 * External dependencies
 */
import { uniqueId } from 'lodash';

/**
 * WordPress dependencies
 */
import type { Action } from '@wordpress/data';

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
import type { TWatermark, TObject, TWatermarkObject } from 'types';

/**
 * Load watermark data action.
 *
 * @param  watermarkID Watermark ID.
 * @return 	           Action object.
 */
export function loadWatermarkData( watermarkID: number ) : Action {
	return {
		type: LOAD_WATERMARK_DATA,
		watermarkID,
	};
}

/**
 * Load watermark success action.
 *
 * @param  watermark       Watermark object.
 * @param  [initial=false] Flag if this is an initial load.
 * @return                  Action object.
 */
export function loadWatermarkSuccess( watermark: TWatermark, initial: boolean = false ) : Action {
	return {
		type: LOAD_WATERMARK_SUCCESS,
		watermark,
		initial,
	};
}

/**
 * Load watermark error action.
 *
 * @param  error Error object.
 * @return       Action object.
 */
export function loadWatermarkError( error: TObject ) : Action {
	return {
		type: LOAD_WATERMARK_ERROR,
		error,
	};
}

/**
 * Edit attribute action.
 *
 * @param  key   Attribute key.
 * @param  value Attribute value.
 * @return       Action object.
 */
export function editAttribute( key: string, value: any ) : Action {
	return {
		type: EDIT_WATERMARK_ATTRIBUTE,
		key,
		value,
	};
}

/**
 * Edit config key action.
 *
 * @param  key          Config key.
 * @param  [value=null] Config value.
 * @return              Action object.
 */
export function editConfig( key: string, value: any = null ) : Action {
	const config = 'string' === typeof key ? { [ key ]: value } : key;

	return {
		type: EDIT_WATERMARK_CONFIG,
		config,
	};
}

/**
 * Set image sizes action.
 *
 * @param  imageSizes Selected mage sizes.
 * @return            Action object.
 */
export function setImageSizes( imageSizes: Array<string> ) : Action {
	return {
		type: SET_IMAGE_SIZES,
		imageSizes,
	};
}

/**
 * Set image types action.
 *
 * @param  imageTypes Selected image types.
 * @return            Action object.
 */
export function setImageTypes( imageTypes: Array<string> ) : Action {
	return {
		type: SET_IMAGE_TYPES,
		imageTypes,
	};
}

/**
 * Set post types action.
 *
 * @param  postTypes Selected post types.
 * @return           Action object.
 */
export function setPostTypes( postTypes: Array<string> ) : Action {
	return {
		type: SET_POST_TYPES,
		postTypes,
	};
}

/**
 * Create watermark object action.
 *
 * @param  object Watermark object data.
 * @return        Action object.
 */
export function createObject( object: TWatermarkObject ) : Action {
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
 * @param  id           Object id.
 * @param  key          Object attribute key or { key: value } object for multiple keys.
 * @param  [value=null] Attribute value if single string key passed as previous param.
 * @return              Action object.
 */
export function editObject( id: number, key: string | { [key: string] : any }, value: any = null ) : Action {
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
 * @param  id Object id.
 * @return    Action object.
 */
export function setActiveObject( id: number ) : Action {
	return {
		type: SET_ACTIVE_OBJECT,
		id,
	};
}
