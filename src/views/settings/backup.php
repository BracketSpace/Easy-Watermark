<?php
/**
 * Backup settings
 *
 * @package easy-watermark
 */

?>
<h2><?php esc_html_e( 'Backup', 'easy-watermark' ); ?></h2>
<label for="ew-backup">
	<input id="ew-backup" name="easy-watermark-settings[backup]" type="checkbox" value="1" <?php checked( $backup ); ?> /> <?php esc_html_e( 'Enable backup', 'easy-watermark' ); ?>
</label>
<table class="form-table hidden" id="backup-settings">
	<tbody>
		<tr valign="top">
			<th scope="row">
				<label for="ew-backupper"><?php esc_html_e( 'Backupper', 'easy-watermark' ); ?></label>
			</th>
			<td>
				<select id="ew-backupper" name="easy-watermark-settings[backupper]">
					<?php	foreach ( $backuppers as $backupper => $details ) : ?>
						<option value="<?php echo esc_attr( $backupper ); ?>" <?php selected( $backupper, $selected_backupper ); ?>><?php echo esc_html( $details['label'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
