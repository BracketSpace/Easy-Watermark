<?php
/**
 * Watermarks page content
 *
 * @package easy-watermark
 */

?>
<div class="watermarks">
	<?php foreach ( $watermarks as $watermark ) : ?>
		<div class="postbox">
			<div class="inside">
				<?php /* translators: watermark name */ ?>
				<h3><a href="<?php echo esc_url( get_edit_post_link( $watermark->ID ) ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Edit “%s”', $watermark->post_title ) ); ?>"><?php echo esc_html( $watermark->post_title ); ?></a></h3>
				<div class="watermark-preview">
					<?php if ( 'image' === $watermark->type ) : ?>
						<?php echo wp_get_attachment_image( $watermark->attachment_id, 'full' ); ?>
					<?php else : ?>
						<img src="<?php echo esc_attr( site_url( sprintf( 'easy-watermark-preview/text-%s.png', $watermark->ID ) ) ); ?>" />
					<?php endif; ?>
				</div>
				<div class="row-actions">
					<?php /* translators: watermark name */ ?>
					<span class="edit"><a href="<?php echo esc_url( get_edit_post_link( $watermark->ID ) ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Edit “%s”', $watermark->post_title ) ); ?>"><?php esc_html_e( 'Edit', 'easy-watermark' ); ?></a> | </span>
					<?php /* translators: watermark name */ ?>
					<span class="delete"><a href="<?php echo esc_url( get_delete_post_link( $watermark->ID, '', true ) ); ?>" class="submitdelete" data-watermark-name="<?php echo esc_attr( $watermark->post_title ); ?>" aria-label="<?php esc_attr_e( sprintf( 'Permanently Delete “%s”', $watermark->post_title ) ); ?>"><?php esc_html_e( 'Delete Permanently', 'easy-watermark' ); ?></a></span>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if ( 2 > $watermarks_count ) : ?>
		<div class="postbox">
			<div class="inside">
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=watermark' ) ); ?>" class="button button-primary button-hero"><?php esc_html_e( 'Add New Watermark', 'easy-watermark' ); ?></a>
			</div>
		</div>
	<?php endif; ?>
</div>
