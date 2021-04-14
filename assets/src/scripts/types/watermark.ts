import { Schema } from "@wordpress/api-fetch";

export type TWatermarkConfig = {
	auto_add: boolean;
	auto_add_all: boolean;
	image_types: Array<string>;
	image_sizes: Array<string>;
	post_types: Array<string>;
};

type TBaseObject = {
	type: 'image' | 'text';
	isActive: boolean;
	offset: {
		x: {
			value: number;
			unit: string;
		};
		y: {
			value: number;
			unit: string;
		};
	};
	alignment: {
		x: string;
		y: string;
	};
	opacity: number;
	angle: number;
};

export type TTextObject = {
	text: string;
	text_color: string;
	text_size: string | number;
} & TBaseObject;

export type TImageObject = {
	attachment_id?: number;
	url?: string;
	mime_type?: string;
	scaling_mode: string;
	scale_down_only: boolean;
	scale: number;
} & TBaseObject;

export type TWatermarkObject = TTextObject | TImageObject;

export type TWatermarkObjects = Array<TWatermarkObject>;

export type TWatermark<T extends Schema.Context = 'edit'> = {
	objects: TWatermarkObjects;
	config: TWatermarkConfig;
	status: Schema.PostStatus | "auto-draft";
} & Omit<Schema.BasePost<T>, "status">;

export type TWatermarkValues = TWatermark[keyof TWatermark];
