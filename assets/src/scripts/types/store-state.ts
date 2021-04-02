import { TAttachment, TWatermark } from 'types';

export type TStoreState = {
	isSidebarOpened: boolean,
	isSaving: boolean,
	activeSidebarTab: string,
	editorState: {
		positionX: number,
		positionY: number,
		scale: number,
		previewImageID?: number,
		previewImage?: TAttachment,
		previewImageSize: string,
	},
	features: {
		fullscreenMode: boolean,
	},
	panels: {
		[key: string]: boolean,
	},
	activeObject?: string,
	initialData: TWatermark,
	editedData: TWatermark,
	edits: {
		past: [],
		current: {},
		future: [],
	},
	imageSizes: Array<string>,
	mimeTypes: Array<string>,
	postTypes: Array<string>,
}
