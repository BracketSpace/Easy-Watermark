
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e('Auto Watermark', 'easy-watermark'); ?></th>
					<td><label for="easy-watermark-autoadd"><input id="easy-watermark-autoadd" name="easy-watermark-settings-general[auto_add]" type="checkbox" value="1" <?php checked('1', $auto_add); ?> /> <?php _e('Add watermark when uploading images', 'easy-watermark'); ?></label></td>
				</tr>
				<tr valign="top" class="auto-add-options" style="display:none;"><th scope="row"><?php _e('Image Types', 'easy-watermark'); ?></th>
					<td>
<label for="image-type-jpg"><input id="image-type-jpg" type="checkbox" name="easy-watermark-settings-general[image_types][]" value="image/jpeg" <?php checked('1', in_array('image/jpeg', $image_types)); ?> /> jpg/jpeg</label><br/>
<label for="image-type-png"><input id="image-type-png" type="checkbox" name="easy-watermark-settings-general[image_types][]" value="image/png" <?php checked('1', in_array('image/png', $image_types)); ?> /> png</label><br/>
<label for="image-type-gif"><input id="image-type-gif" type="checkbox" name="easy-watermark-settings-general[image_types][]" value="image/gif" <?php checked('1', in_array('image/gif', $image_types)); ?> /> gif</label><p class="description"><?php _e('Select image types which should be watermarked', 'easy-watermark'); ?></p></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Backup', 'easy-watermark'); ?></th>
					<td><label for="easy-watermark-backup"><input id="easy-watermark-backup" name="easy-watermark-settings-general[backup]" type="checkbox" value="1" <?php checked('1', $backup); ?> /> <?php _e('Save original images to allow to restore them after watermark was applied', 'easy-watermark'); ?></label><p class="description"><?php _e('This option will use more space on your server. Please consider creating manual backup of original images on your local drive.', 'easy-watermark'); ?></p></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Image Sizes', 'easy-watermark'); ?></th>
					<td>
<label for="image-size-thumb"><input id="image-size-thumb" type="checkbox" name="easy-watermark-settings-general[image_sizes][]" value="thumbnail" <?php checked('1', in_array('thumbnail', $image_sizes)); ?> /> Thumb</label><br/></fieldset>
<label for="image-size-medium"><input id="image-size-medium" type="checkbox" name="easy-watermark-settings-general[image_sizes][]" value="medium" <?php checked('1', in_array('medium', $image_sizes)); ?> /> Medium</label><br/>
<label for="image-size-large"><input id="image-size-large" type="checkbox" name="easy-watermark-settings-general[image_sizes][]" value="large" <?php checked('1', in_array('large', $image_sizes)); ?> /> Large</label><br/>
<label for="image-size-full"><input id="image-size-full" type="checkbox" name="easy-watermark-settings-general[image_sizes][]" value="full" <?php checked('1', in_array('full', $image_sizes)); ?> /> Fullsize</label>
<?php global $_wp_additional_image_sizes;
if(is_array($_wp_additional_image_sizes)) :
foreach($_wp_additional_image_sizes as $sizeName => $size) : ?>
<br/><label for="image-size-<?php echo $sizeName; ?>"><input id="image-size-<?php echo $sizeName; ?>" type="checkbox" name="easy-watermark-settings-general[image_sizes][]" value="<?php echo $sizeName; ?>" <?php checked('1', in_array($sizeName, $image_sizes)); ?> /> <?php echo $sizeName; ?></label>
<?php endforeach; endif; ?>
<p class="description"><?php _e('Select image sizes which should be watermarked', 'easy-watermark'); ?></p></td>
				</tr>
				<tr><th scope="row">
					<label for="ew-watermark-type"><?php _e('Watermark Type', 'easy-watermark'); ?></label>
				</th><td>
					<select id="ew-watermark-type" name="easy-watermark-settings-general[watermark_type]">
						<option value="1" <?php selected('1', $watermark_type) ?>><?php _e('Image', 'easy-watermark'); ?></option>
						<option value="2" <?php selected('2', $watermark_type) ?>><?php _e('Text', 'easy-watermark'); ?></option>
						<option value="3" <?php selected('3', $watermark_type) ?>><?php _e('Image + Text', 'easy-watermark'); ?></option>
					</select>
					<p class="description"><?php _e('Choose, whether to apply image, text, or both.', 'easy-watermark'); ?></p>
				</td>
				</tr>
				<tr><th scope="row"><label for="ew-jpeg-quality"><?php _e('Jpeg Quality', 'easy-watermark'); ?></label></th><td>
					<input type="text" size="3" name="easy-watermark-settings-general[jpg_quality]" id="ew-jpeg-quality" value="<?php echo $jpg_quality; ?>" /><p class="description"><?php _e('Set jpeg quality from 0 (worst quality, smaller file) to 100 (best quality, biggest file)', 'easy-watermark'); ?></p>
				</td>
				<tr valign="top"><th scope="row"><label for="easy-watermark-date-format"><?php _e('Date Format', 'easy-watermark'); ?></label></th>
					<td><input size="5" id="easy-watermark-date-format" name="easy-watermark-settings-general[date_format]" type="text" value="<?php echo $date_format; ?>" /><p class="description"><?php _e('Leave blank to use default date format (see general settings)', 'easy-watermark'); ?></p></td>
				</tr>
				<tr valign="top"><th scope="row"><label for="easy-watermark-time-format"><?php _e('Time Format', 'easy-watermark'); ?></label></th>
					<td><input size="5" id="easy-watermark-time-format" name="easy-watermark-settings-general[time_format]" type="text" value="<?php echo $time_format; ?>" /><p class="description"><?php _e('Leave blank to use default time format', 'easy-watermark'); ?></p></td>
				</tr>
			</table>
			<h3><?php _e('User Roles', 'easy-watermark'); ?></h3>
			<p class="description"><?php _e('Select, which user roles can have a permission to apply watermarks (only roles with permission to upload files are listed here)', 'easy-watermark'); ?></p>
			<table class="form-table">
			<?php
				$roles = $this->plugin->getRoles();

				foreach($roles as $role => $name):
					$name = translate_user_role($name);

				$allowed = isset($allowed_roles[$role]) ? (int) $allowed_roles[$role] : 0;
			?>
				<tr valign="top"><th scope="row"><label for="easy-watermark-role-<?php echo $role ?>"><?php echo $name ?></label></th>
					<td><select id="easy-watermark-role-<?php echo $role ?>" name="easy-watermark-settings-general[allowed_roles][<?php echo $role; ?>]">
						<option <?php selected(1, $allowed); ?> value="1"><?php _e("allow", 'easy-watermark'); ?></option>
						<option <?php selected(0, $allowed); ?> value="0"><?php _e('deny', 'easy-watermark'); ?></option>
					</select></td>
				</tr>
				<?php endforeach; ?>
				<tr valign="top"><th scope="row"><?php _e('Allow to Auto Watermark', 'easy-watermark'); ?></th>
					<td><label for="easy-watermark-autoadd-perm"><input id="easy-watermark-autoadd-perm" name="easy-watermark-settings-general[auto_add_perm]" type="checkbox" value="1" <?php checked('1', $auto_add_perm); ?> /> <?php _e("Check this to allow watermarking on upload to every user. If unchecked, 'Auto Watermark' function will be dependent on above role-based settings.", 'easy-watermark'); ?></label></td>
				</tr>
			</table>
			<h3><?php _e('Post Types', 'easy-watermark'); ?></h3>
			<p class="description"><?php _e('Select what post type attachments should be automatically watermarked', 'easy-watermark'); ?></p>
			<table class="form-table">
				<tr valign="top">
					<td colspan="2"><label for="ew-select-all-post-types"><input type="checkbox" id="ew-select-all-post-types" value="all" /> <?php _e('Select All', 'easy-watermark');  ?></label></td>
				</tr>
				<tr valign="top"><td colspan="2">
<label for="post-type-unattached"><input class="ew-post-type" id="post-type-unattached" type="checkbox" name="easy-watermark-settings-general[allowed_post_types][]" value="unattached" <?php checked('1', in_array('unattached', $allowed_post_types)); ?> /> <?php _e('Unattached Images'); ?> <span class="description">(<?php _e('uploaded through the media library page'); ?>)</span></label><br/>
<label for="post-type-post"><input class="ew-post-type" id="post-type-post" type="checkbox" name="easy-watermark-settings-general[allowed_post_types][]" value="post" <?php checked('1', in_array('post', $allowed_post_types)); ?> /> <?php _e('Posts'); ?></label><br/>
<label for="post-type-page"><input class="ew-post-type" id="post-type-page" type="checkbox" name="easy-watermark-settings-general[allowed_post_types][]" value="page" <?php checked('1', in_array('page', $allowed_post_types)); ?> /> <?php _e('Pages'); ?></label><br/>
				<?php
					$post_types = $this->plugin->getPostTypes('object');

					foreach($post_types as $post_type => $params) : if(post_type_supports($post_type, 'editor') || post_type_supports($post_type, 'thumbnail')) :
				?>
<label for="post-type-<?php echo $post_type; ?>"><input class="ew-post-type" id="post-type-<?php echo $post_type; ?>" type="checkbox" name="easy-watermark-settings-general[allowed_post_types][]" value="<?php echo $post_type; ?>" <?php checked('1', in_array($post_type, $allowed_post_types)); ?> /> <?php echo $params->labels->name; ?></label><br/>
				<?php endif; endforeach; ?>
					</td>
				</tr>
			</table>
