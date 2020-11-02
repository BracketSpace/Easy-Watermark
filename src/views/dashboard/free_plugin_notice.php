<?php
/**
 * Free plugin notice
 *
 * @package easy-watermark
 */

?>
<div class="notice notice-warning notice-alt"><p>
	<?php
	printf(
		/* translators: %1$s is Easy Watermark and %2$s is Easy Watermark Pro */
		esc_html__( 'There are both %1$s and %2$s plugins enabled. Please deactivate free version in order to use %2$s features.', 'easy-watermark' ),
		sprintf( '<strong>%s</strong>', 'Easy Watermark' ),
		sprintf( '<strong>%s</strong>', 'Easy Watermark Pro' )
	);
	?>
</p></div>
