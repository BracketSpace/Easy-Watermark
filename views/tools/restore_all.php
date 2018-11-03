
				<h3><?php _e('Restore original images', 'easy-watermark'); ?></h3>

				<div id="ew-info">
					<div id="message" class="updated below-h2">
					<?php if(current_user_can('edit_others_posts')): ?>
						<p><?php _e('You are about to restore all original images. This action will remove all watermarks ever applied. Are you sure you want to do this?', 'easy-watermark'); ?></p>
					<?php else : ?>
						<p><?php _e('You are about to restore original images for attachments you ever uploaded. This action This action will remove all watermarks ever applied. Are you sure you want to do this?', 'easy-watermark'); ?></p>
					<?php endif; ?>
					</div>

					<a class="button-primary" href="#" id="restore-all-proceed"><?php _e('Proceed', 'easy-watermark'); ?></a> <a class="button-secondary" href="<?php echo admin_url('/upload.php?page=easy-watermark'); ?>"><?php _e('Cancel', 'easy-watermark'); ?></a>
				</div>

				<div id="ew-finished" class="updated below-h2">
					<p><?php echo sprintf(__('%s images restored.', 'easy-watermark'), '<span class="count"></span>'); ?> <?php echo sprintf(__('%s or %s'), '<a href="' . admin_url('upload.php?page=easy-watermark') . '">' . __('Go back', 'easy-watermark') . '</a>', '<a href="' . admin_url('upload.php') . '">' . __('Go to Media Library', 'easy-watermark') . '</a></p>'); ?>
				</div>

				<div id="ew-progress"><div class="progress-label"><?php _e('Starting...', 'easy-watermark'); ?></div></div>

				<div id="ew-status">
					<p><?php echo sprintf(__('Processed %s of %s images', 'easy-watermark'), '<span class="progress"></span>', '<span class="total"></span>'); ?></p>					
					<p><?php echo sprintf(__('Duration: %s', 'easy-watermark'), '<strong><span class="ew-timer">0</span></strong>'); ?></p>
				</div>

				<div id="ew-console"></div>
