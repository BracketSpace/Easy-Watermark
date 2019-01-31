<div class="scaling-metabox">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Scaling mode', 'easy-watermark' ); ?></th>
				<td>
					<select name="watermark[scaling_mode]" id="watermark-scaling-mode">
						<option value="none" <?php selected( 'none', $scaling_mode ); ?>><?php _e( 'None', 'easy-watermark' ) ?></option>
						<option value="cover" <?php selected( 'cover', $scaling_mode ); ?>><?php _e( 'Cover', 'easy-watermark' ) ?></option>
						<option value="contain" <?php selected( 'contain', $scaling_mode ); ?>><?php _e( 'Contain', 'easy-watermark' ) ?></option>
						<option value="fit_to_width" <?php selected( 'fit_to_width', $scaling_mode ); ?>><?php _e( 'Fit to width', 'easy-watermark' ) ?></option>
						<option value="fit_to_height" <?php selected( 'fit_to_height', $scaling_mode ); ?>><?php _e( 'Fit to height', 'easy-watermark' ) ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Scale down only', 'easy-watermark' ); ?></th>
				<td>
					<label for="scale-down-only">
						<input type="checkbox" id="scale-down-only" name="watermark[scale_down_only]" <?php checked( $scale_down_only ); ?> value="1" /> <?php _e( 'Check this option to scale watermark only if watermark image is larger than the image being watermarked.', 'easy-watermark' ); ?>
					</label>
					<p class="description"><?php _e( 'Note: if this option is not checked watermark image will also get scaled up on bigger images which might result in loss of quality/redability.', 'easy-watermark' ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="hidden">
				<th scope="row"><?php _e( 'Scale', 'easy-watermark' ); ?></th>
				<td>
					<div class="form-field">
						<input size="3" type="number" name="watermark[scale]" value="<?php echo $scale; ?>" />
						<div class="form-field-append">
							<span class="form-field-text"> %</span>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
