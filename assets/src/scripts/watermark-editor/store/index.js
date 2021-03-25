/**
 * WordPress dependencies
 */
import { registerStore, use, plugins } from '@wordpress/data';

/**
 * Internal dependencies
 */
import reducer from './reducer';
import * as actions from './actions';
import * as selectors from './selectors';
import applyMiddlewares from './middlewares';
import { STORE_KEY, STORAGE_KEY } from './constants';

use( plugins.persistence, { storageKey: STORAGE_KEY } );

const store = registerStore( STORE_KEY, {
	reducer,
	actions,
	selectors,
	persist: [ 'features', 'panels', 'isSidebarOpened' ],
} );

applyMiddlewares( store );

export default store;
