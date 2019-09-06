<?php
/**
 * Field layout
 *
 * @package easy-watermark
 */

$description = $field->get( 'description' );

?>
<th scope="row">
	<label for="<?php echo esc_attr( $field->get_id() ); ?>"><?php echo $field->get_label(); // phpcs:ignore ?></label>
</th>
<td>
	<?php echo $field->render_field(); //phpcs:ignore ?>

	<?php if ( $description ) : ?>
		<p class="description"><?php echo $description; // phpcs:ignore ?></p>
	<?php endif; ?>
</td>
