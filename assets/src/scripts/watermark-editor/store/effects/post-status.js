/**
 * Internal dependencies
 */
import { editAttribute } from '../actions';

export default ( action, { getState, dispatch } ) => {
	if ( 'status' !== action.key && 'auto-draft' === getState().initialData.status ) {
		dispatch( editAttribute( 'status', 'draft' ) );
	}
};
