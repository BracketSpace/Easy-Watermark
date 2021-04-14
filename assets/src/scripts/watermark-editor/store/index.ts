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
import type { TStoreState, RemoveReturnTypes, OmitFirstArgs } from 'types';

use( plugins.persistence, { storageKey: STORAGE_KEY } );

const store = registerStore<TStoreState>( STORE_KEY, {
	reducer,
	actions,
	selectors,
	persist: [ 'features', 'panels', 'isSidebarOpened' ],
} );

applyMiddlewares( store );

export type TSelectors = OmitFirstArgs<typeof selectors>;
export type TActions = RemoveReturnTypes<typeof actions>;

declare module '@wordpress/data' {
  function select( key: typeof STORE_KEY ): TSelectors;
  function dispatch( key: typeof STORE_KEY ): TActions;
}

export default store;
