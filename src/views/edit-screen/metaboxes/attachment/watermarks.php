<?php
/**
 * Watermarks metabox
 *
 * @package easy-watermark
 */

?>
<div class="watermarks-metabox">
	<?php if ( $watermarks ) : ?>
		<table>
			<tbody>
				<?php foreach ( $watermarks as $watermark ) : ?>
					<tr>
						<th><?php echo esc_html( $watermark->post_title ); ?></th>
						<td>
							<?php if ( $watermark->is_applied ) : ?>
								<?php esc_html_e( 'Applied', 'easy-watermark' ); ?>
							<?php else : ?>
								<button data-action="apply_single" data-watermark="<?php echo esc_attr( $watermark->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_single-' . $watermark->ID ) ); ?>" class="button-secondary"><?php esc_html_e( 'Apply', 'easy-watermark' ); ?></button>
								<span class="spinner"></span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php if ( ! $all_applied ) : ?>
			<div class="button-wrap">
				<?php if ( empty( $applied_watermarks ) ) : ?>
					<button data-action="apply_all" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_all' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Apply all watermarks', 'easy-watermark' ); ?></button>
				<?php else : ?>
					<button data-action="apply_all" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_all' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Apply remaining watermarks', 'easy-watermark' ); ?></button>
				<?php endif; ?>
				<span class="spinner"></span>
			</div>
		<?php endif; ?>

		<?php if ( '1' === $has_backup ) : ?>
			<div class="button-wrap">
				<button data-action="restore_backup" data-nonce="<?php echo esc_attr( wp_create_nonce( 'restore_backup' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Restore original image', 'easy-watermark' ); ?></button>
				<span class="spinner"></span>
			</div>
		<?php endif ?>
	<?php else : ?>
		<p><?php esc_html_e( 'There are no watermarks configured.', 'easy-watermark' ); ?></p>
		<p><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=watermark' ) ); ?>"><?php esc_html_e( 'Create watermark', 'easy-watermark' ); ?></a></p>
	<?php endif; ?>

	<?php if ( $removed_watermarks ) : ?>
		<p class="description"><?php esc_html_e( 'This image contains watermarks which has been removed:', 'easy-watermark' ); ?></p>
		<ul class="removed-watermarks">
			<?php foreach ( $removed_watermarks as $watermark_name ) : ?>
				<li><?php echo esc_html( $watermark_name ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php do_action( 'easy-watermark/attachment-metabox-content', $watermarks ); ?>
</div>
