/**
 * WordPress dependencies
 */
import { rawShortcut, displayShortcut, shortcutAriaLabel } from '@wordpress/keycodes';

export default {
	toggleSidebar: {
		raw: rawShortcut.primary( ',' ),
		display: displayShortcut.primary( ',' ),
		ariaLabel: shortcutAriaLabel.primary( ',' ),
	},
	save: {
		raw: rawShortcut.primary( 's' ),
		display: displayShortcut.primary( 's' ),
		ariaLabel: shortcutAriaLabel.primary( 's' ),
	},
	toggleFullscreen: {
		raw: rawShortcut.alt( 'enter' ),
		display: displayShortcut.alt( 'enter' ),
		ariaLabel: shortcutAriaLabel.alt( 'enter' ),
	},
};
