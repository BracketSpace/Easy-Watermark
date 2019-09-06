<?php
/**
 * Permissions page
 *
 * @package easy-watermark
 */

?>
<form method="post" id="easy-watermark-permissions-form">
	<?php wp_nonce_field( 'easy-watermark-permissions' ); ?>
	<p><?php esc_html_e( 'Select, which user roles can have a permission to apply watermarks (only roles with permission to upload files are listed here)', 'easy-watermark' ); ?></p>
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
								<label class="ew-switch">
									<input id="watermark-allow-creation-<?php echo esc_attr( $role ); ?>" name="permissions[<?php echo esc_attr( $role ); ?>][create]" type="checkbox" value="1" <?php checked( $details['can_create'] ); ?> />
									<span class="switch left-aligned"></span> <?php esc_html_e( 'Allow to create watermarks', 'easy-watermark' ); ?>
								</label>
							</li>
							<li>
								<label class="ew-switch">
									<input id="watermark-allow-edition-<?php echo esc_attr( $role ); ?>" name="permissions[<?php echo esc_attr( $role ); ?>][edit]" type="checkbox" value="1" <?php checked( $details['can_edit'] ); ?> />
									<span class="switch left-aligned"></span> <?php esc_html_e( 'Allow to edit others watermarks', 'easy-watermark' ); ?>
								</label>
							</li>
							<li>
								<label class="ew-switch">
									<input id="watermark-allow-applying-<?php echo esc_attr( $role ); ?>" name="permissions[<?php echo esc_attr( $role ); ?>][apply]" type="checkbox" value="1" <?php checked( $details['can_apply'] ); ?> />
									<span class="switch left-aligned"></span> <?php esc_html_e( 'Allow to apply watermarks', 'easy-watermark' ); ?>
								</label>
							</li>
						</ul>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>" />
	</p>
</form>
