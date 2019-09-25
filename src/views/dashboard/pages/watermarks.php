<?php
/**
 * Watermarks page content
 *
 * @package easy-watermark
 */

?>
<div class="watermarks ew-grid">
	<?php foreach ( $watermarks as $watermark ) : ?>
		<div class="postbox item">
			<div class="inside">
				<?php /* translators: watermark name */ ?>
				<h3>
					<?php if ( current_user_can( 'edit_others_watermarks' ) || get_current_user_id() === (int) $watermark->post_author ) : ?>
						<?php /* translators: %s is watermark title */ ?>
						<a href="<?php echo esc_url( get_edit_post_link( $watermark->ID ) ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Edit “%s”', $watermark->post_title ) ); ?>"><?php echo esc_html( $watermark->post_title ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $watermark->post_title ); ?>
					<?php endif; ?>
				</h3>
				<div class="watermark-preview">
					<?php if ( 'image' === $watermark->type ) : ?>
						<?php echo wp_get_attachment_image( $watermark->attachment_id, 'full' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_attr( site_url( sprintf( 'easy-watermark-preview/text-%s.png', $watermark->ID ) ) ); ?>" />
					<?php endif; ?>
				</div>
				<div class="row-actions">
					<?php if ( current_user_can( 'edit_others_watermarks' ) || get_current_user_id() === (int) $watermark->post_author ) : ?>
						<?php /* translators: watermark name */ ?>
						<span class="edit"><a href="<?php echo esc_url( get_edit_post_link( $watermark->ID ) ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Edit “%s”', $watermark->post_title ) ); ?>"><?php esc_html_e( 'Edit', 'easy-watermark' ); ?></a> | </span>
					<?php endif; ?>
					<?php if ( current_user_can( 'delete_others_watermarks' ) || get_current_user_id() === (int) $watermark->post_author ) : ?>
						<?php /* translators: watermark name */ ?>
						<span class="delete"><a href="<?php echo esc_url( get_delete_post_link( $watermark->ID, '', true ) ); ?>" class="submitdelete" data-watermark-name="<?php echo esc_attr( $watermark->post_title ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Permanently Delete “%s”', $watermark->post_title ) ); ?>"><?php esc_html_e( 'Delete Permanently', 'easy-watermark' ); ?></a></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if ( 2 > $watermarks_count && current_user_can( 'edit_watermarks' ) ) : ?>
		<div class="postbox item">
			<div class="inside">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=watermark' ) ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Add New Watermark', 'easy-watermark' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
</div>
