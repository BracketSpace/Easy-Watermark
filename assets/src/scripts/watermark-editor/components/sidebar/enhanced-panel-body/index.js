/**
 * WordPress dependencies
 */
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { PanelBody } from '@wordpress/components';
import { Component } from '@wordpress/element';

class EnhancedPanelBody extends Component {
	render() {
		const {
			isPanelOpen,
			children,
			title,
		} = this.props;

		return (
			<PanelBody
				title={ title }
				initialOpen={ isPanelOpen }
				onToggle={ this.props.togglePanel } >
				{ children }
			</PanelBody>
		);
	}
}

export default compose(
	withDispatch( ( dispatch, ownProps ) => ( {
		togglePanel: () => dispatch( 'easy-watermark' ).togglePanel( ownProps.id ),
	} ) ),
	withSelect( ( select, ownProps ) => ( {
		isPanelOpen: select( 'easy-watermark' ).isPanelOpen( ownProps.id ),
	} ) ),
)( EnhancedPanelBody );
