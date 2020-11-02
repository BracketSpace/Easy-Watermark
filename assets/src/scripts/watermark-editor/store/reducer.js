/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';

/**
 * External dependencies
 */
import { flow } from 'lodash';

/**
 * Internal dependencies
 */
import * as reducers from './reducers';
import { PREFERENCES_DEFAULTS } from './defaults';

const createWithInitialState = ( initialState ) => ( reducer ) => {
	return ( state = initialState, action ) => reducer( state, action );
};

export default flow( [
	combineReducers,
	createWithInitialState( PREFERENCES_DEFAULTS ),
] )( reducers );
