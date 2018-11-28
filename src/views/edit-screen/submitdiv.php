<div id="submitpost" class="submitbox">
	<div id="major-publishing-actions">
		<?php if ( 2 > $count || 'publish' == $post->post_status ) : ?>
			<div id="delete-action">
			<?php	if ( current_user_can( "delete_post", $post->ID ) ) : ?>
				<?php if ( !EMPTY_TRASH_DAYS ) {
					$delete_text = __('Delete Permanently');
				}	else {
					$delete_text = __('Move to Trash');
				} ?>
				<a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a>
			<?php endif; ?>
			</div>

			<div id="publishing-action">
				<span class="spinner"></span>
				<?php submit_button( __( 'Save' ), 'primary large', 'publish', false ); ?>
			</div>
			<div class="clear"></div>
		<?php else : ?>
			<p><?php _e( 'You can only configure 2 watermarks. Please edit already existing ones.', 'easy-watermark' ); ?>
		<?php endif; ?>
	</div>
</div>
