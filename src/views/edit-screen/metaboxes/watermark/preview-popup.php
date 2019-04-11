<?php
/**
 * Preview metabox popup
 *
 * @package easy-watermark
 */

?>
<div class="ew-preview-popup">
	<div tabindex="0" class="media-modal">
		<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Close media panel</span></span></button>
		<div class="media-modal-content">
			<div class="media-frame mode-select hide-menu">
				<div class="media-frame-title"><h1><?php esc_html_e( 'Watermark preview', 'easy-watermark' ); ?></h1></div>

				<div class="media-frame-content" data-columns="10">
					<?php foreach ( $images as $src => $label ) : ?>
						<h2><?php echo esc_html( $label ); ?></h2>
						<img src="<?php echo esc_attr( $src ); ?>" />
					<?php endforeach; ?>
				</div>

			</div>
		</div>
	</div>
	<div class="media-modal-backdrop"></div>
</div>
