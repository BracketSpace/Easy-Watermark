<?php
/**
 * Watermark type selector
 *
 * @package easy-watermark
 */

?>

<div class="watermark-type-selector">
	<h2><?php esc_html_e( 'Watermark Type', 'easy-watermark' ); ?></h2>
	<div class="buttons">
		<input type="radio" name="watermark[type]" class="watermark-type" id="watermark-type-image" value="image" <?php checked( 'image', $type ); ?>  />
		<label for="watermark-type-image" class="button first"><?php esc_html_e( 'Image', 'easy-watermark' ); ?></label>
		<input type="radio" name="watermark[type]" class="watermark-type" id="watermark-type-text" value="text" <?php checked( 'text', $type ); ?> />
		<label for="watermark-type-text" class="button last"><?php esc_html_e( 'Text', 'easy-watermark' ); ?></label>
	</div>
</div>
