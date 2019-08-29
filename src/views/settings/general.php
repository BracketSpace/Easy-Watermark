<?php
/**
 * General settings
 *
 * @package easy-watermark
 */

?>
<h2><?php esc_html_e( 'General', 'easy-watermark' ); ?></h2>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="ew-jpeg-quality"><?php esc_html_e( 'Jpeg Quality', 'easy-watermark' ); ?></label>
			</th>
			<td>
				<input type="number" min="0" max="100" step="1" size="3" name="easy-watermark-settings[jpeg_quality]" id="ew-jpeg-quality" value="<?php echo esc_attr( $jpeg_quality ); ?>" />
				<p class="description"><?php esc_html_e( 'Set jpeg quality from 0 (worst quality, smaller file) to 100 (best quality, biggest file)', 'easy-watermark' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Filter srcset', 'easy-watermark' ); ?>
				<p class="description"><?php echo esc_html_x( 'for watermarked images', 'Continuation of "Filter srcset" setting label.', 'easy-watermark' ); ?></p>
			</th>
			<td>
				<label class="ew-switch">
					<input id="filter-srcset" name="easy-watermark-settings[filter_srcset]" type="checkbox" value="1" <?php checked( $filter_srcset ); ?> />
					<span class="switch left-aligned"></span>
				</label>
				<p class="description">
					<?php echo esc_html_x( 'Srcset attribute contains information about other image sizes and lets the browser decide which image to display based on the screen size.', '"Filter srcset" setting description line 1', 'easy-watermark' ); ?><br />
					<?php echo esc_html_x( 'This is good in general but it might cause problems if some watermarks are applied only to certain image sizes.', '"Filter srcset" setting description line 2', 'easy-watermark' ); ?><br />
					<?php echo esc_html_x( 'With this option enabled srcset attribute will only contain image sizes watermarked the same way.', '"Filter srcset" setting description line 3', 'easy-watermark' ); ?><br />
				</p>
			</td>
		</tr>
	</tbody>
</table>
