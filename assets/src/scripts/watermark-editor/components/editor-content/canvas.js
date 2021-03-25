/**
 * WordPress dependencies
 */
import { Component, createRef } from '@wordpress/element';
import { withSelect, withDispatch } from '@wordpress/data';
import { compose } from '@wordpress/compose';

/**
 * External dependencies
 */
import panAndZoomHoc from 'react-pan-and-zoom-hoc';

class Canvas extends Component {
	state = {
		previewImage: null,
		initialFit: false,
	};

	constructor( { previewImageID } ) {
		super( ...arguments );

		this.canvasRef = createRef();

		const attachment = wp.media.attachment( previewImageID );

		if ( attachment.get( 'url' ) ) {
			this.state.previewImage = attachment;
		} else {
			attachment.fetch().then( () => {
				this.setState( {
					previewImage: attachment,
				} );
			} );
		}
	}

	componentDidMount() {
		this.fitScale();
	}

	componentDidUpdate() {
		this.fitScale();
	}

	fitScale() {
		if ( this.state.initialFit || ! this.canvasRef.current ) {
			return;
		}

		const canvas = this.canvasRef.current;

		const scaleX = canvas.clientWidth / this.currentImage.width;
		const scaleY = canvas.clientHeight / this.currentImage.height;

		const selector = 1 > scaleX || 1 > scaleY ? Math.min : Math.max;
		const scale = selector( scaleX, scaleY );

		this.props.setEditorScale( scale );
		this.setState( { initialFit: true } );
	}

	render() {
		const { imageSize, x, y, scale, objects } = this.props;
		const { previewImage } = this.state;

		console.log( { objects } );

		let canvasWrapStyle, imageWrapStyle;

		if ( previewImage ) {
			this.currentImage = previewImage.get( 'realSizes' )[ imageSize ];

			const translateX = -x + 0.5;
			const translateY = -y + 0.5;

			canvasWrapStyle = {
				transform: `scale(${ scale }) translate(${
					translateX * 100
				}%, ${ translateY * 100 }%)`,
			};

			imageWrapStyle = {
				width: this.currentImage.width,
				height: this.currentImage.height,
			};
		}

		return (
			<div className="pan-scale-handler">
				{ !! previewImage && (
					<div
						className="watermark-editor-canvas-wrap"
						ref={ this.canvasRef }
						style={ canvasWrapStyle }
					>
						<div className="watermark-editor-canvas">
							<div
								className="watermark-editor-image"
								style={ imageWrapStyle }
							>
								<img
									alt=""
									draggable="false"
									src={ this.currentImage.url }
									width={ this.currentImage.width }
									height={ this.currentImage.height }
								/>
							</div>
						</div>
					</div>
				) }
			</div>
		);
	}
}

export default compose(
	withDispatch( ( dispatch ) => ( {
		setEditorScale: dispatch( 'easy-watermark' ).setEditorScale,
	} ) ),
	withSelect( ( select ) => {
		const { getEditorPreviewImageSize, getObjects } = select(
			'easy-watermark'
		);

		return {
			imageSize: getEditorPreviewImageSize(),
			objects: getObjects(),
		};
	} ),
	panAndZoomHoc
)( Canvas );
