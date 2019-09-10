<?php
/**
 * Auto Watermark warning
 *
 * @package easy-watermark
 */

// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

?>
<div class="notice notice-warning">
	<p>
		<strong><?php esc_html_e( 'Easy Watermark', 'easy-watermark' ); ?>:</strong><br />
		<?php esc_html_e( 'All images uploaded on this page will be watermarked by Auto Watermark feature.', 'easy-watermark' ); ?><br />
		<?php
		$link_text = esc_html_x( 'Grid Mode', 'link text for media library in grid mode', 'easy-watermark' );
		$link      = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'upload.php?mode=grid' ) ), $link_text );

		/* translators: link to medial library in grid mode with text "Grid Mode" */
		printf( esc_html__( 'Use %s instead, so you can enable/disable Auto Watermark feature.', 'easy-watermark' ), $link );
		?>
	</p>
</div>
