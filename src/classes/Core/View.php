<?php
/**
 * View class
 *
 * @package easy-watermark
 */

 namespace EasyWatermark\Core;

class View {
	/**
	 * @var  string  view name
	 */
	private $name;

	/**
	 * @var  array  params
	 */
	private $params = [];

	/**
	 * @var  string  views path
	 */
	private $path;

	/**
	 * Constructor
	 */
	public function __construct( $name = '', $params = [] ) {
		$this->name = $name;
		$this->$params = $params;

		$this->path = EW_DIR_PATH . '/src/views/';
	}

	/**
	 * Sets single param
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed   previous value if exists or null
	 */
	public function setParam( $key, $value ) {
		$previous = isset( $this->params[ $key ] ) ? $this->params[ $key ] : null;

		$this->params[ $key ] = $value;

		return $previous;
	}

	/**
	 * Sets params
	 *
	 * @param  array  $key
	 * @return array  previous params
	 */
	public function setParams( $params ) {
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
		$filename = $this->path . $this->name . '.php';

		if ( ! file_exists( $filename ) ) {
			wp_die( __( 'View file does not exist: ', 'easy-watermark' ) . $this->name, __( 'View not found', 'easy-watermark' ) );
		}

		ob_start();

		extract( $this->params );

		include $filename;

		return ob_get_clean();
	}

	/**
	 * Prints view
	 *
	 * @return void
	 */
	public function print() {
		echo $this->render();
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
