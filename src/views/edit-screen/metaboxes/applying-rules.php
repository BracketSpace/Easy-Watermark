<div class="applying-rules-metabox">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Image Sizes', 'easy-watermark' ); ?></th>
				<td>
					<ul>
						<?php foreach($available_image_sizes as $size => $label ) : ?>
							<li>
								<label for="image-size-<?php echo $size; ?>">
									<input id="image-size-<?php echo $size; ?>" type="checkbox" name="watermark[image_sizes][]" value="<?php echo $size; ?>" <?php checked('1', in_array($size, $image_sizes)); ?> /> <?php echo $label; ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="description"><?php _e( 'Select which image sizes should be watermarked', 'easy-watermark' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<h3><?php _e( 'Auto Watermark', 'easy-watermark' ); ?></h3>
	<p><?php _e( 'Auto Watermark option allows to apply watermark automatically during image upload. Below you can configure this behavior.', 'easy-watermark' ); ?></p>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Auto Watermark', 'easy-watermark' ); ?></th>
				<td>
					<label for="watermark-autoadd">
						<input id="watermark-autoadd" name="watermark[auto_add]" type="checkbox" value="1" <?php checked('1', $auto_add); ?> /> <?php _e( 'Automatically apply this watermark during image upload', 'easy-watermark' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top" class="hidden">
				<th scope="row"><?php _e( 'Image types', 'easy-watermark' ); ?></th>
				<td>
					<ul>
						<?php foreach($available_mime_types as $type => $label ) : ?>
							<li>
								<label for="image-type-<?php echo $type; ?>">
									<input id="image-type-<?php echo $type; ?>" type="checkbox" name="watermark[image_types][]" value="<?php echo $type; ?>" <?php checked('1', in_array($type, $image_types)); ?> /> <?php echo $label; ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
					<p class="description"><?php _e( 'Select which image types should be automatically watermarked', 'easy-watermark' ); ?></p>
				</td>
			</tr>
			<tr valign="top" class="hidden">
				<th scope="row"><?php _e( 'Post types', 'easy-watermark' ); ?></th>
				<td>
					<ul>
						<li>
							<label for="post-type-unattached">
								<input class="ew-post-type" id="post-type-unattached" type="checkbox" name="watermark[post_types][]" value="unattached" <?php checked('1', in_array('unattached', $post_types)); ?> /> <?php _e( 'Unattached Images', 'easy-watermark' ); ?>
								<span class="description">(<?php _e( 'uploaded through the media library page' , 'easy-watermark' ); ?>)</span>
							</label>
						</li>

						<?php foreach ( $available_post_types as $post_type => $params ) : ?>
							<?php if ( post_type_supports( $post_type, 'editor' ) || post_type_supports( $post_type, 'thumbnail' ) ) : ?>
								<li>
									<label for="post-type-<?php echo $post_type; ?>">
										<input class="ew-post-type" id="post-type-<?php echo $post_type; ?>" type="checkbox" name="watermark[post_types][]" value="<?php echo $post_type; ?>" <?php checked('1', in_array($post_type, $post_types)); ?> /> <?php echo $params->labels->name; ?>
									</label>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<p class="description"><?php _e( 'Select what post type attachments should be automatically watermarked', 'easy-watermark' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Allow for all users', 'easy-watermark'); ?></th>
				<td>
					<label for="watermark-autoadd-all">
						<input id="watermark-autoadd-all" name="watermark[auto_add_all]" type="checkbox" value="1" <?php checked( '1', $auto_add_all ); ?> /> <?php _e( 'Check this to enable Auto Watermark for all users', 'easy-watermark' ); ?>
					</label>
					<p class="description"><?php _e( 'If unchecked, Auto Watermark function will depend on the role settings.', 'easy-watermark' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
