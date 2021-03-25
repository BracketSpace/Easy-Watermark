/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';
import { Button } from '@wordpress/components';

class SaveButton extends Component {
	render() {
		const { save, isSaving, isSaveable } = this.props;
		const label = isSaving ? __( 'Saving…' ) : __( 'Save' );

		const isDisabled = isSaving || ! isSaveable;

		const onClick = () => {
			if ( isDisabled ) {
			}

			save();
		};

		return (
			<Button
				aria-disabled={ isDisabled }
				className="ew-save-button"
				isBusy={ isSaving }
				isPrimary
				onClick={ onClick }
			>
				{ label }
			</Button>
		);
	}
}

export default compose(
	withDispatch( ( dispatch ) => ( {
		save: dispatch( 'easy-watermark' ).save,
	} ) ),
	withSelect( ( select ) => {
		const { isSaveable, isSaving } = select( 'easy-watermark' );

		return {
			isSaveable: isSaveable(),
			isSaving: isSaving(),
		};
	} )
)( SaveButton );
