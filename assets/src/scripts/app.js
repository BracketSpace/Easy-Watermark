import WatermarkEditScreen from './screens/watermark-edit-screen.js'
import AttachmentEditScreen from './screens/attachment-edit-screen.js'
import FormField from './utils/form-field.js'

export default class App {
	constructor() {

		switch ( ew.currentScreen ) {
			case 'watermark':
				new WatermarkEditScreen();
				break
			case 'attachment':
				new AttachmentEditScreen();
				break
		}

		new FormField();

	}
}
