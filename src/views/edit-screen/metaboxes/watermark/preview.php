<?php
/**
 * Preview metabox
 *
 * @package easy-watermark
 */

?>
<div class="preview-metabox">
	<span class="spinner"></span>
	<p class="image-selector">
		<a href="#" class="select-preview-image" data-choose="<?php esc_attr_e( 'Choose preview image', 'easy-watermark' ); ?>" data-button-label="<?php esc_attr_e( 'Set as preview image', 'easy-watermark' ); ?>" data-change-label="<?php echo esc_attr( $change_label ); ?>"><?php echo esc_html( $link_label ); ?></a>
	</p>
	<div class="content-wrap">
		<div class="preview-wrap" data-src="<?php echo esc_attr( site_url( sprintf( 'easy-watermark-preview/image-%s.png', $post->ID ) ) ); ?>" data-has-image="<?php echo esc_attr( $has_image ); ?>"></div>
		<p class="description"><?php esc_html_e( 'Click on image to show fullsize preview.', 'easy-watermark' ); ?></p>
	</div>
	<?php echo $popup; //phpcs:ignore ?>
</div>
