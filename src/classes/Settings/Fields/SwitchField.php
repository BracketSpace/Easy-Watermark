<?php
/**
 * Settings Switch Field
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings\Fields;

use EasyWatermark\Core\View;

/**
 * SwitchField class
 */
class SwitchField extends Checkbox {

	/**
	 * Renders Field
	 *
	 * @return string
	 */
	public function render_field() {
		return new View( 'dashboard/settings/fields/switch', [
			'input' => parent::render_field(),
			'field' => $this,
		] );
	}
}
