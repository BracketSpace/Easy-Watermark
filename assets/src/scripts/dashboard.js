/**
 * Internal dependencies
 */
import Settings from './pages/settings';
import Watermarks from './pages/watermarks';
import Tools from './pages/tools';

import '../styles/dashboard.scss';

/**
 * External dependencies
 */
import $ from 'jquery';

$( document ).ready( () => {
	const currentTab = $( 'a.nav-tab-active' ).data( 'tab' );

	switch ( currentTab ) {
		case 'settings':
			new Settings;
			break;
		case 'watermarks':
			new Watermarks;
			break;
		case 'tools':
			new Tools;
			break;
	}
} );
