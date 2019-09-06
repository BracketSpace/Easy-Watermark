<?php
/**
 * Settings page content
 *
 * @package easy-watermark
 */

?>
<form method="post" action="options.php" id="easy-watermark-settings-form">
	<?php settings_fields( 'easy-watermark-settings' ); ?>

	<?php
	foreach ( $sections as $section ) {
		$section->render();
	}
	?>

	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>" />
	</p>
</form>
