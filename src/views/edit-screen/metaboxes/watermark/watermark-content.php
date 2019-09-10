<?php
/**
 * Watermark content metabox
 *
 * @package easy-watermark
 */

global $post;

?>
<div class="watermark-content-metabox">

	<div class="image-content">
		<input class="watermark-id" name="watermark[attachment_id]" type="hidden" value="<?php echo esc_html( $attachment_id ); ?>" />
		<input class="watermark-url" name="watermark[url]" type="hidden" value="<?php echo esc_attr( $url ); ?>" />
		<input class="watermark-mime-type" name="watermark[mime_type]" type="hidden" value="<?php echo esc_attr( $mime_type ); ?>" />

		<div class="select-image-button">
			<a class="button-secondary" data-choose="<?php esc_attr_e( 'Choose Watermark Image', 'easy-watermark' ); ?>" data-button-label="<?php esc_attr_e( 'Set as Watermark Image', 'easy-watermark' ); ?>" href="#"><?php esc_html_e( 'Select/Upload Image', 'easy-watermark' ); ?></a>
			<p class="description"><?php esc_html_e( 'Note: Opacity can be applied to gif and jpg images only.', 'easy-watermark' ); ?></p>
		</div>

		<div class="watermark-image">
			<p class="description"><?php esc_html_e( 'Click on image to change it.', 'easy-watermark' ); ?></p>
			<img src="<?php echo esc_attr( $url ); ?>" />
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php esc_html_e( 'Opacity', 'easy-watermark' ); ?></th>
						<td>
							<div class="form-field">
								<input type="number" size="3" min="0" max="100" step="0.1" name="watermark[opacity]" id="opacity" value="<?php echo esc_attr( $opacity ); ?>" />
								<div class="form-field-append">
									<span class="form-field-text"> %</span>
								</div>
							</div>
							<p class="description opacity-desc hidden"><?php esc_html_e( 'Opacity does not apply to png images.', 'easy-watermark' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="text-content">
		<input class="watermark-text" name="watermark[text]" type="text" value="<?php echo esc_attr( $text ); ?>" placeholder="<?php esc_attr_e( 'Watermark text', 'easy-watermark' ); ?>" />
		<p class="description"><?php esc_html_e( 'You can use placeholders listed in "Placeholders" metabox.', 'easy-watermark' ); ?></p>
		<div class="text-preview" data-src="<?php echo esc_attr( site_url( sprintf( 'easy-watermark-preview/text-%s.png', $post->ID ) ) ); ?>"></div>
	</div>

</div>
