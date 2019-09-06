/**
 * External dependencies
 */
import vex from 'vex-js/src/vex.combined';
import 'vex-js/dist/css/vex.css';

/**
 * Internal dependencies
 */
import '../../styles/components/_vex.scss';

/* global ew */

vex.defaultOptions.className = 'vex-theme-ew';
vex.defaultOptions.contentClassName = 'postbox';

vex.dialog.buttons.YES = {
	...vex.dialog.buttons.YES,
	className: 'button-primary',
	text: ew.i18n.yes,
};

vex.dialog.buttons.OK = {
	...vex.dialog.buttons.YES,
	className: 'button-primary',
	text: ew.i18n.ok,
};

vex.dialog.buttons.NO = {
	...vex.dialog.buttons.NO,
	className: 'button',
	text: ew.i18n.no,
};

export default vex;

export function confirm( message = '', callback = () => {} ) {
	return vex.dialog.confirm( {
		message,
		callback,
		buttons: [
			vex.dialog.buttons.YES,
			vex.dialog.buttons.NO,
		],
	} );
}

export function alert( message = '', callback = () => {} ) {
	return vex.dialog.alert( {
		message,
		callback,
		buttons: [
			vex.dialog.buttons.OK,
		],
	} );
}
