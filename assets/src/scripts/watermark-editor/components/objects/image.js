/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';
import { compose } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import objectHOC from './object';

class ImageObject extends Component {
	render() {
		return (
			<div className="image-object">
			</div>
		);
	}
}

export default compose(
	objectHOC
)( ImageObject );
