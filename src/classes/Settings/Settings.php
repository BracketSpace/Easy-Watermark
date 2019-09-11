<?php
/**
 * Settings class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Settings;

use EasyWatermark\Backup\Manager as BackupManager;
use EasyWatermark\Core\Plugin;
use EasyWatermark\Core\View;
use EasyWatermark\Traits\Hookable;
use underDEV\Utils\Singleton;

/**
 * Settings class
 */
class Settings extends Singleton {

	use Hookable;

	/**
	 * Settings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Option key
	 *
	 * @var string
	 */
	private $option_key;

	/**
	 * Sections collection
	 *
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Constructor
	 */
	protected function __construct() {
		$this->hook();
		$this->register_sections();
		$this->load_settings();
	}

	/**
	 * Gets option key
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->add_section( new Section( __( 'General', 'easy-watermark' ), 'general' ) );
		do_action( 'easy-watermark/settings/register', $this );
	}

	/**
	 * Loads settings
	 *
	 * @return void
	 */
	public function load_settings() {

		if ( null === $this->settings ) {
			$settings_raw = get_option( $this->get_option_key() );
			$settings     = [];

			foreach ( $this->get_sections() as $section_slug => $section ) {
				foreach ( $section->get_fields() as $field_slug => $field ) {
					if ( isset( $settings_raw[ $section_slug ][ $field_slug ] ) ) {
						$field->set_value( $settings_raw[ $section_slug ][ $field_slug ] );
					}

					$settings[ $section_slug ][ $field_slug ] = $field->get_value();
				}
			}

			$this->settings = $settings;
		}

	}

	/**
	 * Registers settings
	 *
	 * @action easy-watermark/settings/register/general 5
	 *
	 * @param  Section $section Settings section.
	 * @return void
	 */
	public function register_fields( $section ) {

		$section->add_field( new Fields\Number( [
			'label'       => __( 'Jpeg Quality', 'easy-watermark' ),
			'slug'        => 'jpeg_quality',
			'description' => __( 'Set jpeg quality from 0 (worst quality, smaller file) to 100 (best quality, biggest file). Set -1 for default quality.', 'easy-watermark' ),
			'default'     => -1,
			'min'         => -1,
			'max'         => 100,
			'step'        => 1,
		] ) );

	}

	/**
	 * Gets option key
	 *
	 * @return string
	 */
	public function get_option_key() {
		if ( ! $this->option_key ) {
			$this->option_key = Plugin::get()->get_slug() . '-settings';
		}

		return $this->option_key;
	}

	/**
	 * Adds section
	 *
	 * @param Section $section Section instance.
	 * @throws \Exception If section is not instance of Section class.
	 * @return void
	 */
	public function add_section( $section ) {
		if ( ! $section instanceof Section ) {
			/* translators: section variable type */
			throw new \Exception( sprintf( __( 'Section must be an instance of EasyWatermark\\Settings\\Section. %s given', 'easy-watermark' ), gettype( $section ) ) );
		}

		$section->set_settings( $this );
		$this->sections[ $section->get_slug() ] = $section;
	}

	/**
	 * Gets section object
	 *
	 * @param  string $slug Section slug.
	 * @return Section|false
	 */
	public function get_section( $slug ) {
		return isset( $this->sections[ $slug ] ) ? $this->sections[ $slug ] : false;
	}

	/**
	 * Gets array of sections
	 *
	 * @return array
	 */
	public function get_sections() {
		return $this->sections;
	}

	/**
	 * Registers settings
	 *
	 * @action admin_init
	 *
	 * @return void
	 */
	public function register_settings() {

		register_setting(
			$this->option_key,
			$this->option_key,
			[ $this, 'sanitize_settings' ]
		);

	}

	/**
	 * Sanitizes settings
	 *
	 * @param  array $settings Settings array.
	 * @return array
	 */
	public function sanitize_settings( $settings ) {

		$sanitized = [];

		foreach ( $this->get_sections() as $section_slug => $section ) {
			foreach ( $section->get_fields() as $field_slug => $field ) {
				$value = isset( $settings[ $section_slug ][ $field_slug ] ) ? $settings[ $section_slug ][ $field_slug ] : null;

				$sanitized[ $section_slug ][ $field_slug ] = $field->sanitize( $value );
			}
		}

		return $sanitized;

	}

	/**
	 * Returns settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Creates settings link on plugins list
	 *
	 * @filter plugin_action_links_easy-watermark/easy-watermark.php
	 *
	 * @param  array  $links Action links.
	 * @param  string $file  Plugin file.
	 * @return array
	 */
	public function plugin_action_links( $links, $file ) {

		return array_merge( [
			'<a href="tools.php?page=easy-watermark&tab=settings">' . __( 'Settings' ) . '</a>',
		], $links );

	}

	/**
	 * Gets setting value
	 *
	 * @param  string $key Settings key.
	 * @return mixed
	 */
	public function get_setting( $key ) {

		$parts = explode( '/', $key );

		if ( 2 === count( $parts ) ) {
			$section = $parts[0];
			$field   = $parts[1];

			if ( isset( $this->settings[ $section ][ $field ] ) ) {
				return $this->settings[ $section ][ $field ];
			}
		}

	}
}
