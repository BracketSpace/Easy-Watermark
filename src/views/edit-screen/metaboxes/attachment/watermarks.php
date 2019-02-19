<?php
/**
 * Watermarks metabox
 *
 * @package easy-watermark
 */

?>
<div class="watermarks-metabox">
	<div class="error-message"></div>
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
				<button data-action="apply_all" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_all' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Apply not applied watermarks', 'easy-watermark' ); ?></button>
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

	<?php do_action( 'easy_watermark/attachment_metabox_content' ); ?>
</div>
