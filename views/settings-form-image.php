
			<table class="form-table">
				<tr valign="top"><th scope="row"><label for="easy-watermark-url"><?php _e('Watermark Image', 'easy-watermark'); ?></label></th>
					<td><input id="easy-watermark-url" class="regular-text" name="easy-watermark-settings-image[watermark_url]" type="hidden" value="<?php echo $watermark_url; ?>" /><input id="easy-watermark-id" name="easy-watermark-settings-image[watermark_id]" type="hidden" value="<?php echo $watermark_id; ?>" /><input id="easy-watermark-mime" name="easy-watermark-settings-image[watermark_mime]" type="hidden" value="<?php echo $watermark_mime; ?>" />
<?php
if(empty($watermark_url)) :
?>
<a class="button-secondary" id="select-image-button" data-choose="<?php _e('Choose Watermark Image', 'easy-watermark'); ?>" data-button-label="<?php _e('Set as Watermark Image', 'easy-watermark'); ?>" href="#"><?php _e('Select/Upload Image', 'easy-watermark'); ?></a><p class="description">	<?php _e('Note: If you want to upload a new image, make sure that "Auto watermark" option is unticked or text watermark is not set. Otherwise uploaded image will be watermarked.', 'easy-watermark'); ?></p>
<br/>
<input id="easy-watermark-position_y" name="easy-watermark-settings-image[position_y]" type="hidden" value="<?php echo $position_y; ?>" />
<input id="easy-watermark-position_x" name="easy-watermark-settings-image[position_x]" type="hidden" value="<?php echo $position_x; ?>" />
<input id="easy-watermark-position_y" name="easy-watermark-settings-image[offset_y]" type="hidden" value="<?php echo $offset_y; ?>" />
<input id="easy-watermark-position_x" name="easy-watermark-settings-image[offset_x]" type="hidden" value="<?php echo $offset_x; ?>" />
<input id="easy-watermark-opacity" name="easy-watermark-settings-image[opacity]"  type="hidden" value="<?php echo $opacity; ?>" />
<?php else : ?>
<img id="watermark-preview" style="max-height:200px;width:auto;cursor:pointer;" src="<?php echo $watermark_url; ?>" />
<p class="description"><?php _e('Click on image to change it.', 'easy-watermark'); ?> <a href="#" class="remove-image"><?php _e('Remove image', 'easy-watermark'); ?></a><br />
<?php _e('Note: If you want to upload a new image, make sure that "Auto watermark" option is unticked, or remove current image and unset text watermark first. Otherwise uploaded image will be watermarked.', 'easy-watermark'); ?></p>
				</td>
				</tr>
				<tr valign="top" class="watermark-options"><th scope="row"><?php _e('Image Alignment', 'easy-watermark'); ?></th><td>
					<div id="alignmentbox">
					<label for="alignment-1" id="alignment-1-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="1" id="alignment-1" <?php checked('1', $alignment); ?> /></label>
					<label for="alignment-2" id="alignment-2-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="2" id="alignment-2" <?php checked('2', $alignment); ?> /></label>
					<label for="alignment-3" id="alignment-3-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="3" id="alignment-3" <?php checked('3', $alignment); ?> /></label>
					<label for="alignment-4" id="alignment-4-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="4" id="alignment-4" <?php checked('4', $alignment); ?> /></label>
					<label for="alignment-5" id="alignment-5-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="5" id="alignment-5" <?php checked('5', $alignment); ?> /></label>
					<label for="alignment-6" id="alignment-6-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="6" id="alignment-6" <?php checked('6', $alignment); ?> /></label>
					<label for="alignment-7" id="alignment-7-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="7" id="alignment-7" <?php checked('7', $alignment); ?> /></label>
					<label for="alignment-8" id="alignment-8-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="8" id="alignment-8" <?php checked('8', $alignment); ?> /></label>
					<label for="alignment-9" id="alignment-9-label"><input type="radio" name="easy-watermark-settings-image[alignment]" value="9" id="alignment-9" <?php checked('9', $alignment); ?> /></label>
					</div>
				</td></tr>
				<tr><th scope="row"><?php _e('Scaling Mode', 'easy-watermark'); ?></th><td>
					<select name="easy-watermark-settings-image[scale_mode]" id="ew-scale-mode">
						<option value="none" <?php selected('none', $scale_mode); ?>><?php _e('None', 'easy-watermark') ?></option>
						<option value="fill" <?php selected('fill', $scale_mode); ?>><?php _e('Fill', 'easy-watermark') ?></option>
						<option value="fit" <?php selected('fit', $scale_mode); ?>><?php _e('Fit', 'easy-watermark') ?></option>
						<option value="fit_to_width" <?php selected('fit_to_width', $scale_mode); ?>><?php _e('Fit to Width', 'easy-watermark') ?></option>
						<option value="fit_to_height" <?php selected('fit_to_height', $scale_mode); ?>><?php _e('Fit to Height', 'easy-watermark') ?></option>
					</select><p class="description"><?php _e('Select how to scale watermark image.', 'easy-watermark'); ?></p>
					<label for="ew-scale-to-smaller"><input type="checkbox" size="3" id="ew-scale-to-smaller" name="easy-watermark-settings-image[scale_to_smaller]" <?php checked($scale_to_smaller); ?> /> <?php _e('Scale to Smaller', 'easy-watermark'); ?></label>
					<p class="description"><?php _e('If this is checked, watermark will be scaled only for images smaller than watermark image.', 'easy-watermark'); ?></p>
				</td></tr>
				<tr id="ew-scale-row"><th scope="row">
					<label for="ew-scale"><?php _e('Scale', 'easy-watermark'); ?></label></th><td>
					<input type="text" size="3" id="ew-scale" name="easy-watermark-settings-image[scale]" value="<?php echo $scale; ?>" /> %
				</td></tr>
				<tr valign="top" class="watermark-options"><th scope="row"><?php _e('Image Offset', 'easy-watermark'); ?></th><td>
					<label for="easy-watermark-position-offset_x"><?php _e('x', 'easy-watermark'); ?>: </label>
					<input size="3" type="text" id="easy-watermark-position-offset_x" name="easy-watermark-settings-image[offset_x]" value="<?php echo $offset_x; ?>" /><br />
					<label for="easy-watermark-position-offset_y"><?php _e('y', 'easy-watermark'); ?>: </label>
					<input type="text" size="3" id="easy-watermark-position-offset_y" name="easy-watermark-settings-image[offset_y]" value="<?php echo $offset_y; ?>"/><p class="description"><?php _e('Offset can be defined in pixels (just numeric value) or as percentage (e.g. \'33%\')', 'easy-watermark'); ?></p>
				</td></tr>
				<tr valign="top" class="watermark-options"><th scope="row"><?php _e('Opacity', 'easy-watermark'); ?></th><td><input id="easy-watermark-opacity" name="easy-watermark-settings-image[opacity]" size="3" type="text" value="<?php echo $opacity; ?>" /> %<p class="description"><?php _e('Opacity does not affect the png images with alpha chanel.', 'easy-watermark'); ?></p>
<?php endif; ?>
				</td>
				</tr>
			</table>
