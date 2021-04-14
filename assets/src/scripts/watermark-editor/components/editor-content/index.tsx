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
import React from 'react';
import { createErrorNotice, removeNotice } from 'wordpress__notices/store/actions';

/**
 * Internal dependencies
 */
import Canvas from './canvas';
import type { TAttachment, TPosition } from 'types';
import type { TSelectors, TActions } from 'watermark-editor/store';

export type TEditorContentProps = {
	createErrorNotice: typeof createErrorNotice;
	position: TPosition;
	previewImageID: number | undefined;
	removeNotice: typeof removeNotice;
	scale: number;
} & Pick<TActions, "setEditorPosition" | "setEditorPositionScale" | "setEditorPreviewImage">;

export type TEditorContentState = {
	loading: boolean,
	initialized: boolean,
	width: number,
	height: number,
};

/**
 * EditorContent Component
 *
 * @augments React.Component
 */
class EditorContent extends Component<TEditorContentProps, TEditorContentState> {
	/**
	 * Notice IDs generated wit _.uniqueID
	 * This is needed to be able to remove created notices.
	 */
	noticeIds: Array<string> = [];

	/**
	 * Attachment model
	 */
	attachment?: TAttachment;

	/**
	 * React node ref
	 */
	ref = createRef<HTMLDivElement>();

	/**
	 * Component state
	 */
	state = {
		loading: false,
		initialized: false,
		width: 0,
		height: 0,
	};

	/**
	 * Indicate if component is mounted.
	 */
	_isMounted: boolean = false;

	/**
	 * @function constructor
	 */
	constructor(props: TEditorContentProps ) {
		super(props);

		if ( props.previewImageID ) {
			this.state.loading = true;
			this.loadPreviewImage( props.previewImageID );
		}
	}

	/**
	 * Loads attachment used as preview image (editor background)
	 */
	async loadPreviewImage( id: number ) : Promise<void> {
		console.log(wp.media);

		const attachment = wp.media.attachment( id );

		if ( ! attachment ) {
			return;
		}

		if ( ! attachment.get( 'url' ) ) {
			this.setState( { loading: true } );

			try {
				await attachment.fetch();

				this.attachment = attachment;
			} catch ( e ) {
				this.handleInvalidAttachment();
			}

			this.setState( { loading: false } );
		}
	}

	/**
	 * Displays error notification if selected background image does not exist.
	 * This might happen if the editor was used before and selected image has
	 * been removed from WordPress Media Library in the meantime.
	 */
	@boundMethod
	handleInvalidAttachment() : void {
		const id = uniqueId( 'ew_notice_' );

		this.props.createErrorNotice(
			__(
				'Selected background image is no longer available. Please choose another image.',
				'easy-watermark'
			),
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
	 * @param x     X coordinate
	 * @param y     Y coordinate
	 * @param scale Scale
	 */
	@boundMethod
	handlePanAndZoom( x: number, y: number, scale: number ) : void {
		this.props.setEditorPositionScale( { x, y, scale } );
	}

	/**
	 * Sets position information to store
	 *
	 * @param x     X coordinate
	 * @param y     Y coordinate
	 */
	@boundMethod
	handlePanMove( x: number, y: number ) : void {
		this.props.setEditorPosition( { x, y } );
	}

	/**
	 * Handles background selection
	 *
	 * @function handleBackgroundSelect
	 * @param  {number}               id Attachment ID
	 * @return {void}
	 */
	@boundMethod
	handleBackgroundSelect( { id: attachmentId }: {id: number} ) {
		const attachment = wp.media.attachment( attachmentId );

		if ( ! attachment.get( 'hasAllSizes' ) ) {
			const noticeId = uniqueId( 'ew_notice_' );

			this.props.createErrorNotice(
				__(
					'Selected image is not available in every size. Please choose larger image.',
					'easy-watermark'
				),
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
	 * @return {void}
	 */
	@boundMethod
	removeNotices() {
		for ( const id of this.noticeIds ) {
			this.props.removeNotice( id );
		}

		this.noticeIds = [];
	}

	/**
	 * Mark component as mounted after it did mount and set `initialized` state.
	 *
	 * @return {void}
	 */
	componentDidMount() {
		this._isMounted = true;
		this.setState( { initialized: true } );
	}

	/**
	 * Renders component
	 *
	 * @function render
	 * @return {React.ReactNode} Rendered node
	 */
	render() {
		const { initialized, loading } = this.state;
		const { position, scale } = this.props;

		const content = initialized ? (
			<>
				{ !! this.attachment && (
					<Canvas
						x={ position.x }
						y={ position.y }
						scale={ scale }
						width={ this.ref.current!.clientWidth }
						height={ this.ref.current!.clientHeight }
						minScale={ 0.1 }
						maxScale={ 10 }
						onPanAndZoom={ this.handlePanAndZoom }
						onPanMove={ this.handlePanMove }
						previewImage={ this.attachment }
						passOnProps={ true }
					/>
				) }
				{ ! this.attachment && ! loading && (
					<MediaUpload
						onSelect={ this.handleBackgroundSelect  }
						allowedTypes={ [ 'image' ] }
						render={ ( { open } ) => (
							<div className="editor-background-selector">
								<Button
									isPrimary
									onClick={ () => {
										this.removeNotices();
										open();
									} }
								>
									{ 'Select Preview Image' }
								</Button>
							</div>
						) }
					/>
				) }
			</>
		) : null;

		return (
			<div className="watermark-editor-wrapper" ref={ this.ref }>
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

		const { createErrorNotice, removeNotice } = dispatch( 'core/notices' );

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
	} )
)( EditorContent );
