<?php
/**
 * Watermarks metabox
 *
 * @package easy-watermark
 */

?>
<div class="watermarks-metabox">
	<p><?php esc_html_e( 'This image is used as watermark image in the following watermarks:' ); ?></p>
	<ul>
		<?php foreach ( $used_as_watermark as $watermark_id ) : ?>
			<li><a href="<?php echo esc_attr( admin_url( sprintf( 'post.php?post=%s&action=edit', $watermark_id ) ) ); ?>"><?php echo esc_html( get_the_title( $watermark_id ) ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
