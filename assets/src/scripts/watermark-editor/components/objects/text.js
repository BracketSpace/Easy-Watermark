/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { compose } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import objectHOC from './object';

class TextObject extends Component {
	render() {
		return <div className="text-object"></div>;
	}
}

export default compose( objectHOC )( TextObject );
