<?php
/**
 * Field layout
 *
 * @package easy-watermark
 */

$description = $field->get( 'description' );

?>
<td colspan="2">
	<?php echo $field->render_field(); //phpcs:ignore ?>

	<?php if ( $description ) : ?>
		<p class="description"><?php echo esc_html( $description ); ?></p>
	<?php endif; ?>
</td>
