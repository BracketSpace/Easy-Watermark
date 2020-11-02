/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import { Component, createRef } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { MediaUpload } from '@wordpress/media-utils';
import { withSelect, withDispatch } from '@wordpress/data';

/**
 * External dependencies
 */
import { boundMethod } from 'autobind-decorator';
import { uniqueId } from 'lodash';

/**
 * Internal dependencies
 */
import Canvas from './canvas';

/**
 * EditorContent Component
 *
 * @augments React.Component
 */
class EditorContent extends Component {
	/**
	 * Notice IDs generated wit _.uniqueID
	 * This is needed to be able to remove created notices.
	 *
	 * @type {Array}
	 */
	noticeIds = [];

	/**
	 * Attachment object
	 *
	 * @type {Object}
	 */
	attachment;

	/**
	 * React node ref
	 *
	 * @type {Object}
	 */
	ref = createRef();

	/**
	 * Component state
	 *
	 * @type {Object}
	 */
	state = {
		loading: false,
		initialized: false,
		width: 0,
		height: 0,
	}

	/**
	 * @function constructor
	 * @param  {integer} previewImageID Attachment ID
	 */
	constructor( { previewImageID } ) {
		super( ...arguments );

		if ( previewImageID ) {
			this.state.loading = true;
			this.loadPreviewImage( previewImageID );
		}
	}

	/**
	 * Loads attachment used as preview image (editor background)
	 *
	 * @function loadPreviewImage
	 * @param    {integer} id Attachment ID
	 * @returns  {void}
	 */
	loadPreviewImage( id ) {
		const attachment = wp.media.attachment( id );

		if ( ! attachment ) {
			return;
		}

		if ( ! attachment.get( 'url' ) ) {
			this.setState( { loading: true } );

			attachment.fetch()
				.then( () => this.attachment = attachment )
				.fail( this.handleInvalidAttachment )
				.always( () => this.setState( { loading: false } ) );
		}
	}

	/**
	 * Displays error notification if selected background image does not exist.
	 * This might happen if the editor was used before and selected image has
	 * been removed from WordPress Media Library in the meantime.
	 *
	 * @function handleInvalidAttachment
	 * @returns {void}
	 */
	@boundMethod
	handleInvalidAttachment() {
		const id = uniqueId( 'ew_notice_' );

		this.props.createErrorNotice(
			__( 'Selected background image is no longer available. Please choose another image.', 'easy-watermark' ),
			{
				id,
				isDismissible: false,
			}
		);

		this.noticeIds.push( id );
	}

	/**
	 * Sets position and scale information to store
	 *
	 * @function handlePanAndZoom
	 * @param  {number}         x     X coordinate
	 * @param  {number}         y     Y coordinate
	 * @param  {number}         scale Scale
	 * @returns {void}
	 */
	@boundMethod
	handlePanAndZoom( x, y, scale ) {
		this.props.setEditorPositionScale( { x, y, scale } );
	}

	/**
	 * Sets position information to store
	 *
	 * @function handlePanMove
	 * @param  {number}         x     X coordinate
	 * @param  {number}         y     Y coordinate
	 * @returns {void}
	 */
	@boundMethod
	handlePanMove( x, y ) {
		this.props.setEditorPosition( { x, y } );
	}

	/**
	 * Handles background selection
	 *
	 * @function handleBackgroundSelect
	 * @param  {number}               id Attachment ID
	 * @returns {void}
	 */
	@boundMethod
	handleBackgroundSelect( { id: attachmentId } ) {
		const noticeId = uniqueId( 'ew_notice_' );
		const attachment = wp.media.attachment( attachmentId );

		if ( ! attachment.get( 'hasAllSizes' ) ) {
			this.props.createErrorNotice(
				__( 'Selected image is not available in every size. Please choose larger image.', 'easy-watermark' ),
				{
					id: noticeId,
					isDismissible: false,
				}
			);

			this.noticeIds.push( noticeId );
			return;
		}

		this.attachment = attachment;
		this.props.setEditorPreviewImage( attachment );
	}

	/**
	 * Removes notices
	 *
	 * @function removeNotices
	 * @returns {void}
	 */
	@boundMethod
	removeNotices() {
		for ( const id of this.noticeIds ) {
			this.props.removeNotice( id );
		}

		this.noticeIds = [];
	}

	componentDidMount() {
		this.setState( { initialized: true } );
	}

	/**
	 * Renders component
	 *
	 * @function render
	 * @returns {React.ReactNode} Rendered node
	 */
	render() {
		const {
			initialized,
			loading,
		} = this.state;

		const {
			position,
			scale,
		} = this.props;

		const content = initialized ? (
			<>
				{ !! this.attachment && (
					<Canvas
						x={ position.x }
						y={ position.y }
						scale={ scale }
						width={ this.ref.current.clientWidth }
						height={ this.ref.current.clientHeight }
						minScale={ 0.1 }
						maxScale={ 10 }
						onPanAndZoom={ this.handlePanAndZoom }
						onPanMove={ this.handlePanMove }
						previewImage={ this.attachment }
						passOnProps={ true }
					/>
				) }
				{ ( ! this.attachment && ! loading ) && (
					<MediaUpload
						onSelect={ this.handleBackgroundSelect }
						allowedTypes={ [ 'image' ] }
						render={ ( { open } ) => (
							<div className="editor-background-selector">
								<Button
									isPrimary
									onClick={ () => {
										this.removeNotices();
										open();
									} } >
									{ 'Select Preview Image' }
								</Button>
							</div>
						) }
					/>
				) }
			</>
		) : null;

		return (
			<div
				className="watermark-editor-wrapper"
				ref={ this.ref }
			>
				{ content }
			</div>
		);
	}
}

export default compose(
	withDispatch( ( dispatch ) => {
		const {
			setEditorPosition,
			setEditorPositionScale,
			setEditorPreviewImage,
		} = dispatch( 'easy-watermark' );

		const {
			createErrorNotice,
			removeNotice,
		} = dispatch( 'core/notices' );

		return {
			setEditorPosition,
			setEditorPositionScale,
			setEditorPreviewImage,
			createErrorNotice,
			removeNotice,
		};
	} ),
	withSelect( ( select ) => {
		const {
			getEditorPosition,
			getEditorScale,
			getEditorPreviewImageID,
		} = select( 'easy-watermark' );

		return {
			position: getEditorPosition(),
			scale: getEditorScale(),
			previewImageID: getEditorPreviewImageID(),
		};
	} ),
)( EditorContent );
