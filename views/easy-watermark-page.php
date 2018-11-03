
		<div class="wrap easy-watermark">
			<div id="icon-easy-watermark" class="icon32"><br /></div>
			<h2><?php _e('Easy Watermark Tools', 'easy-watermark'); ?></h2>

			<?php if(isset($current_tool)) :
				include EWVIEWS . EWDS . 'tools' . EWDS . $current_tool . '.php';
			else : ?>
			<div id="easy-watermark-tool-bulk">
				<h3><?php _e('Bulk Watermark', 'easy-watermark'); ?></h3>

				<?php
					if(current_user_can('edit_others_posts'))
						$description = __('Using this tool you can quickly apply watermark to all images in the Media Library.', 'easy-watermark');
					else
						$description = __('Using this tool you can quickly apply watermark to all images you uploaded to the Media Library.', 'easy-watermark');
				?>
				<p><?php echo $description; ?></p>

				<a class="button-primary" href="<?php echo wp_nonce_url(admin_url('/upload.php?page=easy-watermark&watermark_all=1'), 'watermark_all'); ?>"><?php _e('Start', 'easy-watermark'); ?></a><p class="description"><?php _e('Be carefull with this option. If some images alredy has watermark, it will be added though.', 'easy-watermark'); ?></p>
			</div>

			<div id="easy-watermark-tool-restore">
				<h3><?php _e('Restore original images', 'easy-watermark'); ?></h3>

				<?php
					if(current_user_can('edit_others_posts'))
						$description = __('Here you can remove watermark from all images by restoring the original image files.', 'easy-watermark');
					else
						$description = __('Here you can remove watermark from the images you uploaded by restoring the original image files.', 'easy-watermark');
				?>
				<p><?php echo $description; ?></p>

				<a class="button-primary" href="<?php echo wp_nonce_url(admin_url('/upload.php?page=easy-watermark&restore_all=1'), 'restore_all'); ?>"><?php _e('Restore', 'easy-watermark'); ?></a>

			</div>

			<?php endif; ?>






			<?php /*
			if(isset($_GET['_wpnonce'])) :
				if(wp_verify_nonce($_GET['_wpnonce'], 'watermark_all_confirmed') && isset($_GET['watermark_all']) && ($output = $this->watermark_all())) :
				?>
			<div id="message" class="updated below-h2">
				<p><?php _e('Watermark successfully added.', 'easy-watermark'); ?> <a href="<?php echo admin_url('upload.php') ?>"><?php _e('Go to Media Library', 'easy-watermark'); ?></a></p>
			</div>
				<?php
					echo $output;
				else: ?>
			<div id="message" class="updated below-h2">
			<?php if(current_user_can('edit_others_posts')): ?>
				<p><?php _e('You are about to watermark all images in the library. This action can not be undone. Are you sure you want to do this?', 'easy-watermark'); ?></p>
			<?php else : ?>
				<p><?php _e('You are about to watermark all images you have uploaded ever. This action can not be undone. Are you sure you want to do this?', 'easy-watermark'); ?></p>
			<?php endif; ?>
			</div>


			<a class="button-primary" href="<?php echo wp_nonce_url(admin_url('/upload.php?page=easy-watermark&watermark_all=1'), 'watermark_all_confirmed'); ?>"><?php _e('Proceed', 'easy-watermark'); ?></a> <a class="button-secondary" href="<?php echo admin_url('/upload.php?page=easy-watermark'); ?>"><?php _e('Cancel', 'easy-watermark'); ?></a>
				<?php endif;
			else :
			?>
			<?php
			endif;
*/
		?>
		</div>
