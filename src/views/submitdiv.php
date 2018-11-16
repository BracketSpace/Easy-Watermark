<div class="action-icons submitbox" id="submitpost">
	<input class="button button-primary button-save" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" id="publish" name="publish">
	<?php if ( current_user_can( 'delete_post', $post->ID ) ) : ?>
		<a class="button button-secondary button-delete" href="<?php echo get_delete_post_link( $post->ID ); ?>" title="<?php esc_html_e( 'Delete' ); ?>"><?php esc_html_e( 'Delete' ); ?></a>
	<?php endif; ?>
</div>
