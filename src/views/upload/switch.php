<?php
/**
 * Auto Watermark switch
 *
 * @package easy-watermark
 */

?>

<?php if ( 'tools_page_easy-watermark' !== get_current_screen()->id ) : ?>
	<div class="ew-watermark-all-switch">
		<label class="ew-switch">
		<input id="ew-auto-watermark" class="ew-auto-watermark" type="checkbox" name="ew-auto-watermark" checked />
		<span class="switch left-aligned"></span> <?php esc_html_e( 'Auto Watermark', 'easy-watermark' ); ?>
		</label>
	</div>
<?php endif; ?>
