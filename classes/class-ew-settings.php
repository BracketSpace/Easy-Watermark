<?php
/**
 * This file is a part of EasyWatermark Wordpress plugin.
 * @see readme.txt
 */

class EW_Settings
{
	private $fonts = array(
			'Arial.ttf' => 'Arial',
			'Arial_Black.ttf' => 'Arial Black',
			'Comic_Sans_MS.ttf' => 'Comic Sans MS',
			'Courier_New.ttf' => 'Courier New',
			'Georgia.ttf' => 'Georgia',
			'Impact.ttf' => 'Impact',
			'Tahoma.ttf' => 'Tahoma',
			'Times_New_Roman.ttf' => 'Times New Roman',
			'Trebuchet_MS.ttf' => 'Trebuchet MS',
			'Verdana.ttf' => 'Verdana',
	);

	private static $defaults = array(
		'general' => array(
			'auto_add' => '1',
			'auto_add_perm' => '1',
			'allowed_post_types' => array('post', 'page', 'unattached'),
			'allowed_roles' => array('author' => 1, 'editor' => 2),
			'date_format' => null,
			'time_format' => null,
			'image_types' => array('image/jpeg', 'image/png', 'image/gif'),
			'image_sizes' => array('medium', 'large', 'full'),
			'watermark_type' => 3,
			'jpg_quality' => 75,
			'backup' => '1'
		),
		'image' => array(
			'watermark_url' => null,
			'watermark_id' => null,
			'watermark_path' => null,
			'watermark_mime' => null,
			'position_x' => 'center',
			'position_y' => 'middle',
			'alignment' => 5,
			'offset_x' => 100,
			'offset_y' => 100,
			'opacity' => 100,
			'scale_mode' => 'none',
			'scale_to_smaller' => 'false',
			'scale' => 100
		),
		'text' => array(
			'position_x' => 'center',
			'position_y' => 'middle',
			'alignment' => 5,
			'offset_x' => 0,
			'offset_y' => 0,
			'opacity' => 60,
			'color' => '000000',
			'font' => 'Arial.ttf',
			'size' => 24,
			'angle' => 0,
			'text' => ''
		)
	);

	private $settings = array();

	private $plugin;

	private $tabs;

	private $donationLink = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=wojtek%40szalkiewicz%2epl&lc=GB&item_name=Easy%20Watermark%20Wordpress%20Plugin&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted';

	public static function getDefaults($section = false){
		if($section && isset(self::$defaults[$section]))
			return self::$defaults[$section];

		return self::$defaults;
	}

	public function __construct($plugin){

		add_action('admin_menu', array($this, 'add_options_page'));
		add_action('admin_init', array($this, 'register_settings'));
		add_filter('plugin_action_links', array($this, 'settings_link'), 10, 2);
		add_filter('plugin_row_meta', array($this, 'plugin_row_meta'), 10, 2);

		$this->tabs = array(
			'general' => __('General', 'easy-watermark'),
			'image' => __('Image', 'easy-watermark'),
			'text' => __('Text', 'easy-watermark'),
		);

		foreach($this->tabs as $name => $caption){
			$section = get_option($plugin->getSlug() . '-settings-' . $name);
			$this->settings[$name] = array_merge(self::$defaults[$name], $section);
		}

		$this->plugin = $plugin;

		$this->checkWatermarkImage();

		$plugin->setSettings($this->settings);
	}

	public function set($key, $val){
		$this->settings[$key] = $val;
		update_option($this->plugin->getSlug() . '-settings-' . $key, $val);
	}

	public function get($key){
		return $this->settings[$key];
	}

	public function add_options_page(){
 		$page_id = add_options_page(__('Easy Watermark', 'easy-watermark'), __('Easy Watermark', 'easy-watermark'), 'manage_options', 'easy-watermark-settings', array($this, 'settings_page'));

		add_action('load-' . $page_id, array($this, 'add_help_tab'));
	}

	public function add_help_tab(){
		if(isset($_GET['tab']) && $_GET['tab'] == 'text'){
			$screen = get_current_screen();

			$author = $this->get_help_tab('author');
			$user = $this->get_help_tab('user');
			$general = $this->get_help_tab('general');
			$image = $this->get_help_tab('image');

			$screen->add_help_tab(array(
				'id'	=> 'ew_placeholders_author',
				'title'	=> __('Author placeholders'),
				'content'	=> $author,
			));
			$screen->add_help_tab(array(
				'id'	=> 'ew_placeholders_user',
				'title'	=> __('User placeholders'),
				'content'	=> $user,
			));
			$screen->add_help_tab(array(
				'id'	=> 'ew_placeholders_image',
				'title'	=> __('Image placeholders'),
				'content'	=> $image,
			));
			$screen->add_help_tab(array(
				'id'	=> 'ew_placeholders_general',
				'title'	=> __('General placeholders'),
				'content'	=> $general,
			));
		}
	}

	private function get_help_tab($name){
		ob_start();
		include EWVIEWS . EWDS . 'help_tab_placeholders_' . $name . '.php';
		return ob_get_clean();
	}

	public function register_settings(){
		foreach($this->tabs as $name => $caption){
			register_setting(
				$this->plugin->getSlug() . '-settings-' . $name,
				$this->plugin->getSlug() . '-settings-' . $name,
				array($this, 'sanitize_settings_' . $name)
			);
		}
	}

	public function sanitize_settings_general($input){
		if(!isset($input['auto_add']) || $input['auto_add'] !== '1'){
			$input['auto_add'] = false;
		}

		if(!isset($input['auto_add_perm']) || $input['auto_add_perm'] !== '1'){
			$input['auto_add_perm'] = false;
		}

		if(!isset($input['image_types'])){
			$input['image_types'] = array();
		}

		if(!isset($input['image_sizes'])){
			$input['image_sizes'] = array();
		}

		$input = wp_parse_args($input, $this->settings['general']);

		return $input;
	}

	public function sanitize_settings_image($input){

		if(!empty($input['watermark_url'])){
			if(isset($input['old-manager'])){
				// old wordpress media library, we have only image url
				global $wpdb;

				$row = $wpdb->get_row("
					SELECT ID, post_mime_type
					FROM $wpdb->posts
					WHERE $wpdb->posts.post_type = 'attachment'
					AND $wpdb->posts.guid = '{$input['image']['url']}'
				");

				$input['watermark_id'] = $row->ID;
				$input['watermark_mime'] = $row->post_mime_type;
				unset($input['old-manager']);
			}

			$input['watermark_path'] = get_attached_file($input['watermark_id']);
		}

		if(isset($input['alignment'])) :

		switch($input['alignment']){
			case '1':
				$input['position_x'] = 'left';
				$input['position_y'] = 'top';
				break;
			case '2':
				$input['position_x'] = 'center';
				$input['position_y'] = 'top';
				break;
			case '3':
				$input['position_x'] = 'right';
				$input['position_y'] = 'top';
				break;
			case '4':
				$input['position_x'] = 'left';
				$input['position_y'] = 'middle';
				break;
			case '5':
				$input['position_x'] = 'center';
				$input['position_y'] = 'middle';
				break;
			case '6':
				$input['position_x'] = 'right';
				$input['position_y'] = 'middle';
				break;
			case '7':
				$input['position_x'] = 'left';
				$input['position_y'] = 'bottom';
				break;
			case '8':
				$input['position_x'] = 'center';
				$input['position_y'] = 'bottom';
				break;
			case '9':
				$input['position_x'] = 'right';
				$input['position_y'] = 'bottom';
				break;
		}

		else :

			$input['position_x'] = 'center';
			$input['position_y'] = 'middle';

		endif;

		if(isset($input['scale_to_smaller'])){
			$input['scale_to_smaller'] = true;
		}
		else {
			$input['scale_to_smaller'] = false;
		}

		return $input;
	}

	public function sanitize_settings_text($input){

		switch($input['alignment']){
			case '1':
				$input['position_x'] = 'left';
				$input['position_y'] = 'top';
				break;
			case '2':
				$input['position_x'] = 'center';
				$input['position_y'] = 'top';
				break;
			case '3':
				$input['position_x'] = 'right';
				$input['position_y'] = 'top';
				break;
			case '4':
				$input['position_x'] = 'left';
				$input['position_y'] = 'middle';
				break;
			case '5':
				$input['position_x'] = 'center';
				$input['position_y'] = 'middle';
				break;
			case '6':
				$input['position_x'] = 'right';
				$input['position_y'] = 'middle';
				break;
			case '7':
				$input['position_x'] = 'left';
				$input['position_y'] = 'bottom';
				break;
			case '8':
				$input['position_x'] = 'center';
				$input['position_y'] = 'bottom';
				break;
			case '9':
				$input['position_x'] = 'right';
				$input['position_y'] = 'bottom';
				break;
		}

		return $input;
	}

	public function settings_page(){
//		wp_enqueue_script('ew-colorpicker', plugin_dir_url(EWBASE . '/index.php') . 'js/colorpicker.js');
		wp_enqueue_script('iris');
		wp_enqueue_script('easy-watermark', plugin_dir_url(EWBASE . '/index.php') . 'js/easy-watermark.js');
		wp_enqueue_style('ew-style', plugin_dir_url(EWBASE . '/index.php') . 'css/style.css');
		wp_enqueue_style('ew-cp-style', plugin_dir_url(EWBASE . '/index.php') . 'css/colorpicker.css');
		if(function_exists('wp_enqueue_media')){
			// load new media manager (since wp 3.5)
			wp_enqueue_media();
			wp_enqueue_script('ew-media-libraby', plugin_dir_url(EWBASE . '/index.php') . 'js/media-library.js');
		}
		else {
			// load old-style thiskbox
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('ew-media-libraby', plugin_dir_url(EWBASE . '/index.php') . 'js/old-media-library.js');
		}

		$fonts = $this->fonts;

		$current_tab = (isset($_GET['tab']) && array_key_exists($_GET['tab'], $this->tabs)) ? $_GET['tab'] : 'general';
		extract($this->settings[$current_tab]);

		include EWVIEWS . EWDS . 'settings-page.php';
	}

	/**
 	 * This method checks if the watermark image exists, if not it will be unset
	 *
	 * @return void
	 */
	private function checkWatermarkImage(){
		$imgFile = $this->settings['image']['watermark_path'];

		if(!empty($imgFile) && !file_exists($imgFile)){
			// Image has been removed
			$this->settings['image']['watermark_path'] = null;
			$this->settings['image']['watermark_url'] = null;
			$this->settings['image']['watermark_id'] = null;
			$this->settings['image']['watermark_mime'] = null;
		}

		$this->set('image', $this->settings['image']);
	}

	function settings_link($links, $file){
		static $this_plugin;

		if (!$this_plugin) {
			$this_plugin = plugin_basename(EWBASE . EWDS . 'index.php');
		}
		if ($file == $this_plugin) {
			$settings_link = '<a href="options-general.php?page=easy-watermark-settings">'.__('Settings').'</a>';
			array_unshift($links, $settings_link);
		}
		return $links;
	}

	function plugin_row_meta($links, $file){
		static $this_plugin;

		if (!$this_plugin) {
			$this_plugin = plugin_basename(EWBASE . EWDS . 'easy-watermark.php');
		}
		if ($file == $this_plugin) {
			$donate_link = '<a href="'.$this->donationLink.'">'.__('Donate', 'easy-watermark').'</a>';
			array_push($links, $donate_link);
		}
		return $links;
	}
}
