/**
 * Internal dependencies
 */
import { saveEditorSettings } from '../actions';

export default ( action, { dispatch } ) => {
	dispatch(
		saveEditorSettings( {
			preview_image: action.attachment.get( 'id' ),
		} )
	);
};
