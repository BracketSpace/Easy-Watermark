import { TAttachment, TWatermark, TObject, TSelectable } from 'types';

export type TEdit = Partial<TWatermark>;
export type TEdits = Array<TEdit>;

export type TEditorState = {
	positionX: number,
	positionY: number,
	scale: number,
	previewImageID?: number,
	previewImage?: TAttachment,
	previewImageSize: string,
};

export type TStoreState = {
	isSidebarOpened: boolean,
	isSaving: boolean,
	activeSidebarTab: string,
	editorState: TEditorState,
	features: TObject<boolean>,
	panels: TObject<boolean>,
	activeObject?: number,
	initialData: TWatermark,
	editedData: TWatermark,
	edits: {
		past: TEdits,
		current: TEdit,
		future: TEdits,
	},
	imageSizes: Array<string>,
	mimeTypes: Array<string>,
	postTypes: TSelectable,
}
