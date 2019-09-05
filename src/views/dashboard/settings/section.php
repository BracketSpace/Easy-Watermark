<?php
/**
 * General settings
 *
 * @package easy-watermark
 */

?>
<h2><?php echo esc_html( $name ); ?></h2>
<table class="form-table">
	<tbody>
		<?php foreach ( $fields as $field ) : ?>
			<?php
			$group  = $field->get( 'group' );
			$layout = $field->get_layout();
			$class  = [
				'ew-field',
				"layout-{$layout}",
			];

			if ( $group ) {
				$class[] = "group-{$group}";
			}

			$class = implode( ' ', $class );
			?>
			<tr class="<?php echo esc_attr( $class ); ?>">
				<?php $field->render(); ?>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
