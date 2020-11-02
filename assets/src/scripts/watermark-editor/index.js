/**
 * WordPress dependencies
 */
import '@wordpress/notices';
import { render } from '@wordpress/element';

/**
 * Internal dependencies
 */
import './store';
import Editor from './components/editor';

render(
	<Editor
		settings={ ew.editorSettings }
		watermarkID={ ew.watermarkID }
	/>,
	document.getElementById( 'watermark-editor' )
);
