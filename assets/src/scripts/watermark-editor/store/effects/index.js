/**
 * Internal dependencies
 */
import setActiveObject from './active-object';
import loadWatermarkData from './data';
import saveEditorSettings from './editor-settings';
import saveEditorPreviewImage from './editor-state';
import setPostStatus from './post-status';
import save from './save';
import {
	CREATE_OBJECT,
	EDIT_WATERMARK_ATTRIBUTE,
	LOAD_WATERMARK_DATA,
	SAVE,
	SAVE_EDITOR_SETTINGS,
	SET_ACTIVE_OBJECT,
	SET_EDITOR_PREVIEW_IMAGE,
} from '../action-types';

export default {
	[ CREATE_OBJECT ]: setActiveObject,
	[ EDIT_WATERMARK_ATTRIBUTE ]: setPostStatus,
	[ LOAD_WATERMARK_DATA ]: loadWatermarkData,
	[ SAVE ]: save,
	[ SAVE_EDITOR_SETTINGS ]: saveEditorSettings,
	[ SET_ACTIVE_OBJECT ]: setActiveObject,
	[ SET_EDITOR_PREVIEW_IMAGE ]: saveEditorPreviewImage,
};
