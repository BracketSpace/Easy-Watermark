<?php
/**
 * Switch field
 *
 * @package easy-watermark
 */

?>
<label class="ew-switch">
	<?php echo $input; // phpcs:ignore ?>
	<span class="switch left-aligned"></span>
	<?php
	if ( 'simple' === $field->get_layout() ) {
		echo esc_html( $field->get_label() );
	}
	?>
</label>
