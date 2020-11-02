/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

function objectHOC( WrappedComponent ) {
	return class extends Component {
		render() {
			return (
				<div className="object-wrap">
					<WrappedComponent { ...this.props } />
				</div>
			);
		}
	};
}

export default objectHOC;
