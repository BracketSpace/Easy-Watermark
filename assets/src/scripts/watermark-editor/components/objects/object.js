/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Higher Order Component for a watermark object.
 *
 * @param  {React.Component|React.FunctionComponent} WrappedComponent Wrapped component.
 * @return {React.Component}                                          React component.
 */
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
