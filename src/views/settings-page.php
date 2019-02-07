<?php
/**
 * Settings page content
 *
 * @package easy-watermark
 */

?>
<div class="wrap easy-watermark">
	<h1><?php esc_html_e( 'Easy Watermark Settings', 'easy-watermark' ); ?></h1>
	<form method="post" action="options.php" id="easy-watermark-settings-form">
		<?php settings_fields( 'easy-watermark-settings' ); ?>

		<?php
		// phpcs:disable
		echo $general;
		echo $permissions;
		// phpcs:enable
		?>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>" />
		</p>
	</form>
</div>
