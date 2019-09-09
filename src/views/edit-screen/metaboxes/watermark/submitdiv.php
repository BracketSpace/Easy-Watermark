<?php
/**
 * Submitdiv metabox
 *
 * @package easy-watermark
 */

?>
<div id="submitpost" class="submitbox">
	<div id="major-publishing-actions">
		<div id="delete-action">
		<?php	if ( current_user_can( 'delete_post', $post->ID ) ) : ?>
			<a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post->ID, '', true ); ?>"><?php esc_html_e( 'Delete Permanently' ); ?></a>
		<?php endif; ?>
		</div>
		<div id="publishing-action">
			<span class="spinner"></span>
			<?php submit_button( __( 'Save' ), 'primary large', 'publish', false ); ?>
		</div>
		<div class="clear"></div>
	</div>
</div>
