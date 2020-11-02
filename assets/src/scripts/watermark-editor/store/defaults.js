
export const PREFERENCES_DEFAULTS = {
	isSidebarOpened: true,
	isSaving: false,
	activeSidebarTab: 'general',
	editorState: {
		positionX: 0.5,
		positionY: 0.5,
		scale: 1,
		previewImageID: null,
		previewImage: null,
		previewImageSize: 'full',
	},
	features: {
		fullscreenMode: false,
	},
	panels: {
		autoWatermark: true,
	},
	activeObject: null,
	initialData: {},
	editedData: {},
	edits: {
		past: [],
		current: {},
		future: [],
	},
	imageSizes: ew.imageSizes,
	mimeTypes: ew.mimeTypes,
	postTypes: ew.postTypes,
};

export const OBJECT_DEFAULTS = {
	offset: {
		x: {
			value: 0,
			unit: 'px',
		},
		y: {
			value: 0,
			unit: 'px',
		},
	},
	alignment: {
		x: 'center',
		y: 'center',
	},
	opacity: 100,
	angle: 0,
};

export const TEXT_OBJECT_DEFAULTS = {
	...OBJECT_DEFAULTS,
	text: '',
	text_color: '#000000',
	text_size: '24',
};

export const IMAGE_OBJECT_DEFAULTS = {
	...OBJECT_DEFAULTS,
	attachment_id: null,
	url: null,
	mime_type: null,
	scaling_mode: 'none',
	scale_down_only: false,
	scale: 100,
};
