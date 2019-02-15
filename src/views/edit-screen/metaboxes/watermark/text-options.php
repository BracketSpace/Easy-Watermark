<?php
/**
 * Text options metabox
 *
 * @package easy-watermark
 */

?>
<div class="text-options-metabox">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row"><?php esc_html_e( 'Font', 'easy-watermark' ); ?></th>
				<td>
					<select name="watermark[font]" id="ew-font">
						<?php foreach ( $available_fonts as $font_name => $label ) : ?>
							<option value="<?php echo esc_attr( $font_name ); ?>" <?php selected( $font_name, $font ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text color', 'easy-watermark' ); ?></th>
				<td>
					<input type="text" maxlength="7" size="7" name="watermark[text_color]" id="text-color" value="<?php echo esc_attr( $text_color ); ?>" />
				</td>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text size', 'easy-watermark' ); ?></th>
				<td>
					<div class="form-field">
						<input type="number" size="3" name="watermark[text_size]" id="text-size" value="<?php echo esc_attr( $text_size ); ?>" />
						<div class="form-field-append">
							<span class="form-field-text"> pt</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text angle', 'easy-watermark' ); ?></th>
				<td>
					<div class="form-field">
						<input type="number" size="3" name="watermark[text_angle]" id="text-angle" value="<?php echo esc_attr( $text_angle ); ?>" />
						<div class="form-field-append">
							<span class="form-field-text"> &deg;</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Opacity', 'easy-watermark' ); ?></th>
				<td>
					<div class="form-field">
						<input type="number" size="3" min="0" max="100" step="0.1" name="watermark[opacity]" id="opacity" value="<?php echo esc_attr( $opacity ); ?>" />
						<div class="form-field-append">
							<span class="form-field-text"> %</span>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
