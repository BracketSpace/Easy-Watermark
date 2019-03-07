<?php
/**
 * Alignment metabox
 *
 * @package easy-watermark
 */

?>
<div class="alignment-metabox">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Alignment', 'easy-watermark' ); ?></th>
				<td>
					<div class="alignment-selector">
						<input type="radio" name="watermark[alignment]" value="top-left" id="alignment-top-left" <?php checked( 'top-left', $alignment ); ?> />
						<label for="alignment-top-left" class="alignment-top-left-label"></label>

						<input type="radio" name="watermark[alignment]" value="top" id="alignment-top" <?php checked( 'top', $alignment ); ?> />
						<label for="alignment-top" class="alignment-top-label"></label>

						<input type="radio" name="watermark[alignment]" value="top-right" id="alignment-top-right" <?php checked( 'top-right', $alignment ); ?> />
						<label for="alignment-top-right" class="alignment-top-right-label"></label>

						<input type="radio" name="watermark[alignment]" value="left" id="alignment-left" <?php checked( 'left', $alignment ); ?> />
						<label for="alignment-left" class="alignment-left-label"></label>

						<input type="radio" name="watermark[alignment]" value="center" id="alignment-center" <?php checked( 'center', $alignment ); ?> />
						<label for="alignment-center" class="alignment-center-label"></label>

						<input type="radio" name="watermark[alignment]" value="right" id="alignment-right" <?php checked( 'right', $alignment ); ?> />
						<label for="alignment-right" class="alignment-right-label"></label>

						<input type="radio" name="watermark[alignment]" value="bottom-left" id="alignment-bottom-left" <?php checked( 'bottom-left', $alignment ); ?> />
						<label for="alignment-bottom-left" class="alignment-bottom-left-label"></label>

						<input type="radio" name="watermark[alignment]" value="bottom" id="alignment-bottom" <?php checked( 'bottom', $alignment ); ?> />
						<label for="alignment-bottom" class="alignment-bottom-label"></label>

						<input type="radio" name="watermark[alignment]" value="bottom-right" id="alignment-bottom-right" <?php checked( 'bottom-right', $alignment ); ?> />
						<label for="alignment-bottom-right" class="alignment-bottom-right-label"></label>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php esc_html_e( 'Offset', 'easy-watermark' ); ?></th>
				<td>
					<div class="form-field">
						<div class="form-field-prepend">
							<span class="form-field-text"><?php esc_html_e( 'x', 'easy-watermark' ); ?>:</span>
						</div>
						<input size="3" type="number" name="watermark[offset][x][value]" value="<?php echo esc_attr( $offset['x']['value'] ); ?>" />
						<div class="form-field-append">
							<input type="hidden" id="watermark-offset-x-unit" name="watermark[offset][x][unit]" value="<?php echo esc_attr( $offset['x']['unit'] ); ?>" />
							<button data-toggle="dropdown"><?php echo esc_html( $offset['x']['unit'] ); ?></button>
							<div class="dropdown-menu" data-target="#watermark-offset-x-unit">
							<a class="dropdown-item" href="#" data-value="px"><?php esc_html_e( 'px', 'easy-watermark' ); ?></a>
							<a class="dropdown-item" href="#" data-value="%"><?php esc_html_e( '%', 'easy-watermark' ); ?></a>
						</div>
						</div>
					</div>
					<div class="form-field">
						<div class="form-field-prepend">
							<span class="form-field-text"><?php esc_html_e( 'y', 'easy-watermark' ); ?>:</span>
						</div>
						<input size="3" type="number" name="watermark[offset][y][value]" value="<?php echo esc_attr( $offset['y']['value'] ); ?>" />
						<div class="form-field-append">
							<input type="hidden" id="watermark-offset-y-unit" name="watermark[offset][y][unit]" value="<?php echo esc_attr( $offset['y']['unit'] ); ?>" />
							<button data-toggle="dropdown"><?php echo esc_html( $offset['y']['unit'] ); ?></button>
							<div class="dropdown-menu" data-target="#watermark-offset-y-unit">
							<a class="dropdown-item" href="#" data-value="px"><?php esc_html_e( 'px', 'easy-watermark' ); ?></a>
							<a class="dropdown-item" href="#" data-value="%"><?php esc_html_e( '%', 'easy-watermark' ); ?></a>
						</div>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
