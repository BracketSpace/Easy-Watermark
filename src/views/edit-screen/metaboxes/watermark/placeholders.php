<?php
/**
 * Placeholders metabox
 *
 * @package easy-watermark
 */

?>
<div class="placeholders-metabox">
	<input type="text" name="ew-search-placeholders" placeholder="<?php esc_attr_e( 'Search placeholders', 'easy-watermark' ); ?>" class="widefat ew-search-placeholders" autocomplete="off" id="ew-search-placeholders">
	<ul class="placeholders-list">
		<?php foreach ( $placeholders as $placeholder ) : ?>
			<li>
				<div class="help">
					<span class="question-mark">?</span>
					<div class="description">
						<div class="description-content">
							<label><?php esc_html_e( 'Example:', 'easy-watermark' ); ?></label>
							<span class="example"><?php echo esc_html( $placeholder->get_example() ); ?></span>
							<i>(<?php echo esc_html( $placeholder->get_value_type() ); ?>)</i>
						</div>
					</div>
				</div>
				<label><?php echo esc_html( $placeholder->get_name() ); ?></label>
				<code data-clipboard-text="<?php echo esc_attr( $placeholder->get_code() ); ?>"><?php echo esc_html( $placeholder->get_code() ); ?></code>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
