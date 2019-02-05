<?php
/**
 * View class
 *
 * @package easy-watermark
 */

namespace EasyWatermark\Core;

/**
 * View class
 */
class View {
	/**
	 * View name
	 *
	 * @var  string
	 */
	private $name;

	/**
	 * Params
	 *
	 * @var  array
	 */
	private $params = [];

	/**
	 * Views path
	 *
	 * @var  string
	 */
	private $path;

	/**
	 * Constructor
	 *
	 * @param string $name    View name.
	 * @param array  $params  View params.
	 */
	public function __construct( $name = '', $params = [] ) {
		$this->name   = $name;
		$this->params = $params;

		$this->path = EW_DIR_PATH . '/src/views/';
	}

	/**
	 * Sets view name
	 *
	 * @param  string $name  View name.
	 * @return void
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}

	/**
	 * Sets single param
	 *
	 * @param  string $key   Param name.
	 * @param  mixed  $value Param value.
	 * @return mixed  previous value if exists or null
	 */
	public function set_param( $key, $value ) {
		$previous = isset( $this->params[ $key ] ) ? $this->params[ $key ] : null;

		$this->params[ $key ] = $value;

		return $previous;
	}

	/**
	 * Sets params
	 *
	 * @param  array $params View params.
	 * @return array previous params
	 */
	public function set_params( $params ) {
		$previous = $this->params;

		$this->params = (array) $params;

		return $previous;
	}

	/**
	 * Renders view
	 *
	 * @return string
	 */
	public function render() {
		if ( ! is_string( $this->name ) ) {
			/* translators: argument type */
			wp_die( sprintf( esc_html__( 'View name should be a string, %s given.', 'easy-watermark' ), esc_html( gettype( $this->name ) ) ), esc_html__( 'Invalid view name type', 'easy-watermark' ) );
		}

		$filename = $this->path . $this->name . '.php';

		if ( ! file_exists( $filename ) ) {
			/* translators: view name */
			wp_die( sprintf( esc_html__( 'View file does not exist: %s', 'easy-watermark' ), esc_html( $this->name ) ), esc_html__( 'View not found', 'easy-watermark' ) );
		}

		ob_start();

		extract( $this->params ); // phpcs:ignore

		include $filename;

		return ob_get_clean();
	}

	/**
	 * Displays view
	 *
	 * @return void
	 */
	public function display() {
		echo $this->render(); // phpcs:ignore
	}

	/**
	 * Magic method for object to string conversion
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->render();
	}
}
