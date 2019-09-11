<?php
/**
 * Page abstract class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Dashboard;

use EasyWatermark\Traits\Hookable;

/**
 * Page class
 */
abstract class Page {

	use Hookable;

	/**
	 * Page title
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Page slug
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Page priority
	 *
	 * @var int
	 */
	protected $priority;

	/**
	 * Permission
	 *
	 * @var string
	 */
	protected $permission = 'apply_watermark';

	/**
	 * Constructor
	 *
	 * @param string $title    Page title.
	 * @param string $slug     Page slug.
	 * @param int    $priority Page priority.
	 */
	public function __construct( $title, $slug = null, $priority = 10 ) {
		$this->hook();

		if ( null === $slug ) {
			$slug = $title;
		}

		$this->title    = $title;
		$this->slug     = sanitize_title( $slug );
		$this->priority = (int) $priority;
	}

	/**
	 * Adds options page
	 *
	 * @filter easy-watermark/dashboard/tabs
	 *
	 * @param  array $tabs Tabs.
	 * @return array
	 */
	public function add_tab( $tabs ) {

		if ( current_user_can( $this->permission ) ) {
			$tabs[ $this->slug ] = [
				'title'    => $this->title,
				'priority' => $this->priority,
			];
		}

		return $tabs;

	}
}
