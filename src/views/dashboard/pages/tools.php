<?php
/**
 * Tools page content
 *
 * @package easy-watermark
 */

?>
<div class="tools ew-grid">
	<div class="postbox item tool-bulk-watermark">
		<div class="inside">
			<h3><?php esc_html_e( 'Bulk Watermark', 'easy-watermark' ); ?></h3>
			<div class="content">
				<p><?php esc_html_e( 'Using this tool you can quickly apply watermark to all images in the Media Library.', 'easy-watermark' ); ?></p>
				<?php if ( count( $watermarks ) ) : ?>
					<?php if ( count( $attachments ) ) : ?>
						<p>
							<select class="watermark">
								<option value="-1"><?php esc_html_e( 'Select Watermark', 'easy-watermark' ); ?></option>
								<?php if ( 1 < count( $watermarks ) ) : ?>
									<option value="all" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_all' ) ); ?>"><?php esc_html_e( 'All Watermarks', 'easy-watermark' ); ?></option>
								<?php endif; ?>
								<?php foreach ( $watermarks as $watermark ) : ?>
									<option value="<?php echo esc_attr( $watermark->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'apply_single-' . $watermark->ID ) ); ?>"><?php echo esc_html( $watermark->post_title ); ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<p><a href="#" class="button-primary"><?php esc_html_e( 'Start', 'easy-watermark' ); ?></a><span class="spinner"></span></p>
						<p class="description"><?php esc_html_e( 'Note: The same watermark will never get applied twice to the same image. This tool is safe to use even if some of the images are already watermarked.', 'easy-watermark' ); ?></p>
					<?php else : ?>
						<p><?php esc_html_e( 'There are no image attachments in your Media Library available for watermarking.', 'easy-watermark' ); ?></p>
					<?php endif; ?>
				<?php else : ?>
					<?php $link = sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=watermark' ), esc_html_x( 'create watermark', 'link text for new watermark page', 'easy-watermark' ) ); ?>
					<?php /* translators: %s is a "create watermark" link */ ?>
					<p><?php printf( esc_html__( 'There are no watermarks configured. Please %s first.', 'easy-watermark' ), $link ); // phpcs:ignore ?></p>
				<?php endif; ?>
			</div>
			<p class="status"></p>
		</div>
	</div>
	<div class="postbox item tool-restore" data-backup-count="<?php echo esc_attr( $backup_count ); ?>">
		<div class="inside">
			<h3><?php esc_html_e( 'Restore Backup', 'easy-watermark' ); ?></h3>
			<div class="content">
				<p><?php esc_html_e( 'Here you can quickly restore backup for all images in Media Library.', 'easy-watermark' ); ?></p>
				<?php /* translators: %s is backed up images count */ ?>
				<p class="hidden has-backup"><?php printf( esc_html__( 'There are %s backed up images in your Media Library.', 'easy-watermark' ), "<span class=\"backup-count\">{$backup_count}</span>" ); // phpcs:ignore ?></p>
				<p class="hidden has-backup"><a href="#" class="button-primary" data-nonce="<?php echo esc_attr( wp_create_nonce( 'restore_backup' ) ); ?>"><?php esc_html_e( 'Restore', 'easy-watermark' ); ?></a><span class="spinner"></span></p>
				<p class="hidden no-backup"><?php esc_html_e( 'There are no backed up images in your Media Library.', 'easy-watermark' ); ?></p>
			</div>
			<p class="status"></p>
		</div>
	</div>
</div>
