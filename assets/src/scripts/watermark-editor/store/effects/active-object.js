/**
 * Internal dependencies
 */
import { toggleSidebarTab, setActiveObject } from '../actions';
import { CREATE_OBJECT, SET_ACTIVE_OBJECT } from '../action-types';

/**
 * Side effect function.
 *
 * It opens "Object" sidebar tab when object is selected and selects an object
 * on its creation.
 *
 * @param {Object}   action          Action being dispatched
 * @param {Object}   params          Additional params
 * @param {Function} params.dispatch Callback to dispatch other actions
 */
export default function ( action, { dispatch } ) {
	switch ( action.type ) {
		case SET_ACTIVE_OBJECT:
			dispatch( toggleSidebarTab( 'object' ) );
			break;
		case CREATE_OBJECT:
			dispatch( setActiveObject( action.object.id ) );
			break;
	}
}
