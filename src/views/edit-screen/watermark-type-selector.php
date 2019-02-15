<?php
/**
 * Watermark type selector
 *
 * @package easy-watermark
 */

?>

<div class="watermark-type-selector">
	<h2><?php esc_html_e( 'Watermark Type', 'easy-watermark' ); ?></h2>
	<div class="buttons">
		<?php $i = 1; ?>
		<?php foreach ( $watermark_types as $type => $params ) : ?>
			<?php
				$classes = [ 'button' ];

			if ( 1 < count( $watermark_types ) ) {
				if ( 1 === $i ) {
					$classes[] = 'first';
				} elseif ( count( $watermark_types ) === $i ) {
					$classes[] = 'last';
				}
			}

				$i++;

			if ( false === $params['available'] ) {
				$classes[] = 'disabled';
			}
			?>
			<input type="radio" name="watermark[type]" class="watermark-type" id="watermark-type-<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( $type ); ?>" <?php checked( $type, $selected_type ); ?><?php disabled( ! $params['available'] ); ?>  />
			<label for="watermark-type-<?php echo esc_attr( $type ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"><?php echo esc_html( $params['label'] ); ?></label>
		<?php endforeach; ?>
	</div>
</div>
