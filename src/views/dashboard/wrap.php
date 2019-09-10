<?php
/**
 * Dashboard page wrap
 *
 * @package easy-watermark
 */

$base_url = admin_url( 'tools.php?page=easy-watermark' );

?>
<div class="wrap easy-watermark">
	<h1><?php esc_html_e( 'Easy Watermark', 'easy-watermark' ); ?></h1>
	<hr class="wp-header-end" />

	<?php if ( count( $tabs ) ) : ?>
		<h2 class="nav-tab-wrapper">
		<?php foreach ( $tabs as $tab => $data ) : ?>
			<a class="nav-tab<?php echo ( $tab === $current_tab ) ? ' nav-tab-active' : null; ?>" data-tab="<?php echo esc_attr( $tab ); ?>" href="<?php echo esc_url( add_query_arg( [ 'tab' => $tab ], $base_url ) ); ?>"><?php echo esc_html( $data['title'] ); ?></a>
		<?php endforeach; ?>
		</h2>
	<?php endif; ?>

	<?php echo $content // phpcs:ignore ?>
	<?php do_action( "easy-watermark/dashboard/{$current_tab}/content" ); ?>
	<?php do_action( 'easy-watermark/dashboard/content', $current_tab ); ?>

</div>
