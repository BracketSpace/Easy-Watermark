<?php
/**
 * Permissions settings
 *
 * @package easy-watermark
 */

?>
<h2><?php esc_html_e( 'Permissions', 'easy-watermark' ); ?></h2>
<p class="description"><?php esc_html_e( 'Select, which user roles can have a permission to apply watermarks (only roles with permission to upload files are listed here)', 'easy-watermark' ); ?></p>
<table class="form-table">
	<tbody>
		<?php
		foreach ( $roles as $role => $details ) :
			$name = translate_user_role( $details['name'] );
			?>
			<tr valign="top">
				<th scope="row">
					<label for="ew-role-<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $name ); ?></label>
				</th>
				<td>
					<ul>
						<li>
							<label for="watermark-allow-creation-<?php echo esc_attr( $role ); ?>">
								<input id="watermark-allow-creation-<?php echo esc_attr( $role ); ?>" name="easy-watermark-settings[permissions][<?php echo esc_attr( $role ); ?>][create]" type="checkbox" value="1" <?php checked( $details['can_create'] ); ?> /> <?php esc_html_e( 'Allow to create watermarks', 'easy-watermark' ); ?>
							</label>
						</li>
						<li>
							<label for="watermark-allow-edition-<?php echo esc_attr( $role ); ?>">
								<input id="watermark-allow-edition-<?php echo esc_attr( $role ); ?>" name="easy-watermark-settings[permissions][<?php echo esc_attr( $role ); ?>][edit]" type="checkbox" value="1" <?php checked( $details['can_edit'] ); ?> /> <?php esc_html_e( 'Allow to edit others watermarks', 'easy-watermark' ); ?>
							</label>
						</li>
						<li>
							<label for="watermark-allow-applying-<?php echo esc_attr( $role ); ?>">
								<input id="watermark-allow-applying-<?php echo esc_attr( $role ); ?>" name="easy-watermark-settings[permissions][<?php echo esc_attr( $role ); ?>][apply]" type="checkbox" value="1" <?php checked( $details['can_apply'] ); ?> /> <?php esc_html_e( 'Allow to apply watermarks', 'easy-watermark' ); ?>
							</label>
						</li>
					</ul>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
