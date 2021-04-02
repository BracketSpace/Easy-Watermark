import type { Attachment } from "../types";

declare global {
	namespace wp {
		const media: {
			model: {
				Attachment: Attachment;
			};

			attachment( id: string | number ) : Attachment;
		};
	}
};
