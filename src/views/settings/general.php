<?php
/**
 * General settings
 *
 * @package easy-watermark
 */

?>
<h2><?php esc_html_e( 'General', 'easy-watermark' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="ew-jpeg-quality"><?php esc_html_e( 'Jpeg Quality', 'easy-watermark' ); ?></label>
			</th>
			<td>
				<input type="number" min="0" max="100" step="1" size="3" name="easy-watermark-settings[jpeg_quality]" id="ew-jpeg-quality" value="<?php echo esc_attr( $jpeg_quality ); ?>" />
				<p class="description"><?php esc_html_e( 'Set jpeg quality from 0 (worst quality, smaller file) to 100 (best quality, biggest file)', 'easy-watermark' ); ?></p>
			</td>
		</tr>
	</tbody>
</table>
