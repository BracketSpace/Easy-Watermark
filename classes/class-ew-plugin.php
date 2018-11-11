<?php
/**
 * This file is a part of Easy Watermark Wordpress plugin.
 * @see readme.txt
 */

/**
 * Main plugin class
 */
class EW_Plugin extends EW_Plugin_Core
{
	/**
	 * @var boolean
	 */
	private static $GDEnabled;

	/**
	 * @var boolean
	 */
	private static $FreeTypeEnabled;

	/**
	 * @var array
	 */
	private $settings;

	/**
	 * @var boolean
	 */
	private $error = false;

	/**
	 * @var array
	 */
	private $allowedMime = array('image/png', 'image/jpeg', 'image/gif');

	/**
	 * @var array
	 */
	private $defaultPostTypes = array('post', 'page', 'unattached');

	/**
	 * @var array
	 */
	private $notices = array();

	/**
	 * @var object  post object for an image
	 */
	private $currentImage;

	/**
	 * @var array messages for different error codes from EasyWatermark class
	 */
	private $ewErrors = array();

	/**
	 * @var boolean
	 */
	private $isBulkAction = false;

	/**
	 * @var boolean  flag to turn off auto watermark function when restoring files
	 */
	private $restoring = false;

	/**
 	 * Loads textdomain for translations,
 	 * adds wordpress actions
	 *
	 * @return void
	 */
	public function __construct(){

		$this->add_action('init', 'plugin_init')
			// This hook was used before version 0.5.1 but it is not called by some plugins (e.g. buddypress-media)
//			->add_action('add_attachment', 'add_watermark_after_upload')
			// now the proper filter is attached direclty as it is called only when new image is uploaded (see add_watermark_after_upload)
			->add_filter('wp_generate_attachment_metadata', null, 10, 2);

		// load admin interface
		if(is_admin()){
			$this->add_action('admin_menu', 'add_media_page')
				->add_action('admin_init')
				->add_filter('media_row_actions', 'add_media_row_action', 10, 3)
//				->add_filter('attachment_fields_to_edit', 'add_attachment_field', 10, 2)
				->add_action('admin_notices')
				->add_action('admin_head-upload.php', 'add_bulk_action_script')
				->add_action('admin_action_ew_add_watermark', 'bulk_action_handler')
				->add_action('admin_print_scripts', 'easy_watermark_style')
				->add_action('manage_media_columns', 'add_media_column')
				->add_action('manage_media_custom_column', null, 10, 2)
				->add_action('add_meta_boxes')
				->add_action('delete_attachment', 'clean_backup')
				->add_action('wp_ajax_watermark_all')
				->add_action('wp_ajax_restore_all');
		}

		$this->test_GD();

		$this->ewErrors = array(
			EasyWatermark::ERROR_SAME_IMAGE_PATHS			=> __('Same image and watermark paths.', 'easy-watermark'),
			EasyWatermark::ERROR_NO_WATERMARK_SET			=> __('No watermark image or text specified.', 'easy-watermark'),
			EasyWatermark::ERROR_NO_INPUT_IMAGE				=> __('No input image specified.', 'easy-watermark'),
			EasyWatermark::ERROR_NOT_ALLOWED_TYPE			=> __('Not allowed image type.', 'easy-watermark'),
			EasyWatermark::ERROR_NO_OUTPUT_FILE_SET			=> __('No output file specified.', 'easy-watermark'),
			EasyWatermark::ERROR_NOT_ALLOWED_OUTPUT_TYPE	=> __('Not allowed output type.', 'easy-watermark'),
			EasyWatermark::ERROR_UNKNOWN					=> __('Could not apply watermark.', 'easy-watermark')
		);

		// Init Freemius.
		ew_fs();

		// Signal that SDK was initiated.
		do_action('ew_fs_loaded');
	}

	/**
 	 * Tests whether the GD library is installed and enabled
	 *
	 * @return void
	 */
	private function test_GD(){
		if(extension_loaded('gd') && function_exists('gd_info')){
			self::$GDEnabled = true;
			$gdinfo = gd_info();
			self::$FreeTypeEnabled = $gdinfo['FreeType Support'];
		}
		else {
			self::$GDEnabled = false;
		}
	}

	/**
 	 * Tells whether the GD library is working
	 *
	 * @return boolean
	 */
	public static function isGDEnabled(){
		return self::$GDEnabled;
	}

	/**
 	 * Tells whether the FreeType library is working
	 *
	 * @return boolean
	 */
	public static function isFreeTypeEnabled(){
		return self::$FreeTypeEnabled;
	}

	/**
 	 * Performs some actions which need to be done before anything else
	 *
	 * @return void
	 */
	public function plugin_init(){
		new EW_Settings($this);
	}

	public function admin_init(){
		if(isset($_GET['page'])){
			if($_GET['page'] == 'easy-watermark-settings' && isset($_GET['tp']) && $_GET['tp'] == 1){
				$this->print_text_preview();
			}
			elseif($_GET['page'] == 'easy-watermark' && isset($_GET['_wpnonce'])){
				if(wp_verify_nonce($_GET['_wpnonce'], 'ew_add_watermark'))
					$this->add_watermark();
				if(wp_verify_nonce($_GET['_wpnonce'], 'ew_mark'))
					$this->mark_image();
				if(wp_verify_nonce($_GET['_wpnonce'], 'ew_restore'))
					$this->restore_single();
			}
		}
	}

	/**
 	 * Returns generated jpeg image with text preview to the browser.
	 * Used on settings page
	 *
	 * @return void
	 */
	private function print_text_preview(){
		if($this->isGDEnabled()) :

		$ew = $this->getEasyWatermark();
		if($this->settings['general']['watermark_type'] == '1'){
			$settings = $this->getTextSettings();

			$fontFile = EWBASE . EWDS . 'fonts' . EWDS . $settings['font'];
			if(file_exists($fontFile))
				$settings['font'] = $fontFile;

			$ew->textSet($settings);
		}

		if(isset($_GET['text'])){
			$text = $this->parseText($_GET['text']);
			$ew->textSet('text', $text);
		}
		if(isset($_GET['size']))
			$ew->textSet('size', $_GET['size']);
		if(isset($_GET['angle']))
			$ew->textSet('angle', $_GET['angle']);
		if(isset($_GET['color']))
			$ew->textSet('color', $_GET['color']);
		if(isset($_GET['opacity']))
			$ew->textSet('opacity', $_GET['opacity']);

		if(isset($_GET['font'])){
			$fontFile = EWBASE . EWDS . 'fonts' . EWDS . $_GET['font'];
			if(file_exists($fontFile))
				$ew->textSet('font', $fontFile);
		}

		$ew->printTextPreview();

		endif;

		exit;
	}

	/**
 	 * Performs watermarking the single attachment
	 *
	 * @return void
	 */
	private function add_watermark(){
		if(isset($_GET['attachment_id']) && $this->isGDEnabled()){
			$post = get_post((int) $_GET['attachment_id']);
			$roles = $this->getAllowedRoles();

			if(!$this->checkRolePermission() || !(current_user_can('edit_others_posts') || $post->post_author == wp_get_current_user()->ID))
				// User doesn't have a premission to add watermark, he was not able to click the link!
				wp_die( __( 'Cheatin&#8217; uh?' ) );

			$url = false;
			if($this->watermark_attachment($post)){
				switch($_GET['r']){
					case 'library':
						$url = admin_url('upload.php?watermarked=1');
						break;
					case 'post':
						$url = admin_url('post.php?post='.$_GET['attachment_id'].'&action=edit&watermarked=1');
						break;
				}
			}
			else {
				switch($_GET['r']){
					case 'library':
						$url = admin_url('upload.php?ew_error='.$this->error);
						break;
					case 'post':
						$url = admin_url('post.php?post='.$_GET['attachment_id'].'&action=edit&ew_error='.$this->error);
						break;
				}
			}
			if($url){
				wp_redirect($url);
				exit;
			}
		}
	}

	/**
 	 * Prints admin notices
	 *
	 * @return void
	 */
	public function admin_notices(){
		if(isset($_GET['watermarked']) && $_GET['watermarked'] == '1'){
			echo '<div class="updated"><p>'.__('Watermark successfully added.', 'easy-watermark').'</p></div>';
		}
		elseif(isset($_GET['marked'])){
			$marked = $_GET['marked'] == 1 ? __('watermarked', 'easy-watermark') : __('not watermarked', 'easy-watermark');
			echo '<div class="updated"><p>'.sprintf(__('An Image has been marked as %s.', 'easy-watermark'), $marked).'</p></div>';
		}
		elseif(isset($_GET['ew_error'])){
			echo '<div class="error"><p>';
			switch($_GET['ew_error']):
				case '1':
					_e('Invalid mime type.', 'easy-watermark');
					break;
				case '2':
					_e('No watermark image selected and no watermark text set.', 'easy-watermark');
					echo ' <a href="'.admin_url('options-general.php?page=easy-watermark-settings').'">';
					_e('Go to settings page', 'easy-watermark');
					echo '</a>';
					break;
				default:
					_e('An error has occurred.', 'easy-watermark');
			endswitch;
			echo '</p></div>';
		}

		if(!self::isGDEnabled() && get_current_screen()->id == 'plugins'){
			echo '<div class="error"><p>'.__('Easy Watermark is active, but requires GD library to work. Please enable this extension.', 'easy-watermark').' <a href="http://www.php.net/manual/en/image.setup.php" target="_blank">'.__('Read more', 'easy-watermark').'</p></div>';
		}

		foreach($this->notices as $msg){
			echo '<div class="' . $msg[0] . '"><p>' . $msg[1] . '</p></div>';
		}
	}

	/**
 	 * Adds wordpress media page
	 *
	 * @return void
	 */
	public function add_media_page(){
		$roles = $this->settings['general']['allowed_roles'];

		if($this->isGDEnabled() && $this->checkRolePermission())
	 		add_media_page( 'Easy Watermark', 'Easy Watermark', 'upload_files', 'easy-watermark', array($this, 'easy_watermark'));
	}

	/**
 	 * Adds javascript code providing 'Add Watermark' bulk action on media page
	 *
	 * @return void
	 */
	function add_bulk_action_script() {
		$roles = $this->settings['general']['allowed_roles'];
		if($this->isGDEnabled() && $this->checkRolePermission()){
			$text = __('Add Watermark', 'easy-watermark');
			echo <<<EOD
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('<option>').val('ew_add_watermark').text('$text').appendTo("select[name='action']");
				jQuery('<option>').val('ew_add_watermark').text('$text').appendTo("select[name='action2']");
			});
		</script>
EOD;
		}
	}

	/**
 	 * Creates 'Add watermark' link for each row in media library
	 *
	 * @return  array
	 */
	public function add_media_row_action($actions, $post, $detached){
		$roles = $this->settings['general']['allowed_roles'];
		if($this->isGDEnabled() && $this->checkRolePermission()
			&& in_array($post->post_mime_type, $this->allowedMime)
			&& get_attached_file($post->ID) != $this->settings['image']['watermark_path']
			&& (current_user_can('edit_others_posts') || $post->post_author == wp_get_current_user()->ID)){



			// Add link if it's supported image type
			$actions['add_watermark'] = '<a href="' . wp_nonce_url(admin_url('upload.php?page=easy-watermark&attachment_id='.$post->ID.'&r=library'), 'ew_add_watermark') . '">'.__('Add Watermark', 'easy-watermark').'</a>';
		}

		return $actions;
	}

	/**
 	 * Creates 'Add watermark' button in media edit view
	 *
	 * @return	array
	 */
	public function add_attachment_field($form_fields, $post){
		if($this->isGDEnabled() && in_array($post->post_mime_type, $this->allowedMime) && $post->ID != $this->settings['image']['watermark_id']){
			// Add link if it's supported image type
			$form_fields = array_reverse($form_fields);
			$form_fields['easy-watermark'] = array(
				'label' => '<a href="'.wp_nonce_url(admin_url('upload.php?page=easy-watermark&attachment_id='.$post->ID.'&r=post'), 'ew_add_watermark').'" class="button-secondary">'.__('Add watermark', 'easy-watermark').'</a>',
				'input' => 'html',
				'html' => ' '
			);
		}

		return array_reverse($form_fields);
	}

	/**
 	 * Handles the ew_add_watermark bulk action
	 * Performs watermarking selected images
	 *
	 * @uses    self::watermark_single
	 * @return  void
	 */
	public function bulk_action_handler(){

		if(empty($_REQUEST['action']) || ($_REQUEST['action'] != 'ew_add_watermark' && $_REQUEST['action2'] != 'ew_add_watermark')){
			return;
		}

		if(empty($_REQUEST['media']) || !is_array($_REQUEST['media'])){
			return;
		}

		check_admin_referer('bulk-media');
		$result = true;
		$this->isBulkAction = true;
		foreach($_REQUEST['media'] as $entry){
			if(!$this->watermark_attachment((int) $entry) && $this->error != 99)
				$result = false;
		}

		if(isset($_GET['_wp_http_referer'])){
			$referer = $_GET['_wp_http_referer'];

			$args = $result ? array('watermarked' => '1') : array('ew_error' => $this->error);
			$referer = add_query_arg($args, remove_query_arg(array('ew_error', 'watermarked'), $referer));

			wp_redirect($referer);
			exit;
		}
	}

	/**
 	 * Watermark image after it was uploaded.
	 * In fact this method only marks that there is an image to watermark,
	 * it's realy watermarked in wp_generate_attachment_metadata filter.
	 * See wp_generate_attachment_metadata() method below.
	 *
	 * This function is deprecated since 0.5.1, it is not needed since the
	 * 'wp_generate_attachment_metadata' filter is called only for newly uploaded files.
	 * Some plugins (e.g. buddypress-media) calls this filter, but not the 'add_attachment' action
	 * so existance of this function caused problems.
	 *
	 * @deprecated
	 * @return	array
	 */
	public function add_watermark_after_upload($id){
		if($this->isGDEnabled() && $this->settings['general']['auto_add']){
			$this->watermark_uploaded = true;
			$this->uploaded_id = $id;
			$this->add_filter('wp_generate_attachment_metadata', null, 10, 2);
		}
	}

	/**
 	 * Filter used to add watermark (it is called after image resizing)
	 *
	 * @param	 array
	 * @param	 integer
	 * @return	 array
	 */
	public function wp_generate_attachment_metadata($metadata, $id){
		if($this->settings['general']['auto_add'] && !$this->restoring
			&& ($this->settings['general']['auto_add_perm'] == '1' || $this->checkRolePermission())
			&& $this->checkPostType()){

			$this->watermark_attachment($id, true, $metadata);

			$this->watermark_uploaded = false;
			$this->uploaded_id = null;
		}

		return $metadata;
	}

	/**
 	 * Performs the action of a single attachment watermarking
	 *
	 * @uses   self::create_watermark
	 * @param  integer  image id
	 * @param  boolean  whether to check mime if it's selected to auto watermarking
	 * @param  array    image meta
	 * @return boolean
	 */
	private function watermark_attachment($post, $checkMime = false, $meta = array()){
		if($this->isGDEnabled()) :
		if($this->settings['image']['watermark_path'] != '' || $this->settings['text']['text'] != null){
			if(is_numeric($post))
				$post = get_post($post);
			$filepath = get_attached_file($post->ID);
			if(!in_array($post->post_mime_type, $this->allowedMime)){
				$this->error = 1;
				return false;
			}
			if($checkMime && !in_array($post->post_mime_type, $this->settings['general']['image_types'])){
				return false;
			}

			return $this->create_watermark($post, $meta);
		}
		else {
			$this->error = 2;
			return false;
		}

		endif;
	}

	public function wp_ajax_watermark_all(){
		check_ajax_referer('watermark_all_confirmed', '_ewnonce');

		$msg = $this->watermark_all();

		$output = array(
			'progress' => get_option('ew-bulk-counter'),
			'message' => $msg
		);

		echo json_encode($output);

		exit;
	}

	/**
 	 * Performs the action of watermarking all images from library
	 *
	 * @uses   self::create_watermark
	 * @return boolean
	 */
	private function watermark_all(){
		if($this->isGDEnabled()) :

		$page = get_option('ew-bulk-page', 0);
		$count = get_option('ew-bulk-counter', 0);
		$total = get_option('ew-bulk-total', 0);

		$limit = 10;
		$offset = $page * $limit;

		global $wpdb;

		$images = $wpdb->get_results($wpdb->prepare("
			SELECT ID, post_mime_type, post_author, post_title
			FROM {$wpdb->posts}
			WHERE post_type = 'attachment'
			AND post_mime_type LIKE %s
			ORDER BY ID ASC LIMIT %d OFFSET %d",

			'image/%', $limit, $offset
		));

		$output = '';
		$skipped = 0;
		if($images && ($this->settings['image']['watermark_path'] != '' || $this->settings['text']['text'])){
			$this->isBulkAction = true;

			foreach($images as $img){
				$this->currentImage = $img;

				$filepath = get_attached_file($img->ID);
				if(!current_user_can('edit_others_posts') && wp_get_current_user()->ID != $img->post_author){
					// No permission to edit this image
					$output .= sprintf(__('No permission to edit file %s. Skipping...', 'easy-watermark'), '<strong>'.$filepath.'</strong>').'<br/>';
					$skipped++;
					continue;
				}
				if(!in_array($img->post_mime_type, $this->allowedMime)){
					$output .= sprintf(__('Not supported mime type of %s. Skipping...', 'easy-watermark'), '<strong>'.$filepath.'</strong>').'<br/>';
					$skipped++;
					continue;
				}

				if($this->create_watermark($img)){
					$output .= sprintf(__('Watermark successfully added to %s', 'easy-watermark'), '<strong>'.$filepath.'</strong>') . '<br/>';
				}
			}
		}
		else return false;

		$output = '<p>'.$output.'</p>';

		$count += $limit;
		if($count > $total) $count = $total;

		update_option('ew-bulk-page', $page + 1);
		update_option('ew-bulk-counter', $count);

		return $output;

		endif;
	}

	/**
 	 * Applies the watermark to the defined image sizes
	 *
	 * @use    self::watermark_single_image()
	 * @param  object  wp post
	 * @return boolean
	 */
	public function create_watermark($post, $meta = array()){

		if(empty($meta)){
			$meta = get_post_meta($post->ID, '_wp_attachment_metadata', true);
		}

		$filepath = get_attached_file($post->ID);
		$filebasename = wp_basename($meta['file']);

		if($this->settings['image']['watermark_path'] != $filepath){

			$this->currentImage = $post;

			if($this->settings['general']['backup']){
				$backup_file = $this->backup_attachment($filepath, $filebasename);
				if(is_string($backup_file)){
					update_post_meta($post->ID, '_ew_backup_file', $backup_file);
				}
			}

			if($this->isBulkAction){
				// Refresh watermark text for each image
				$this->getEasyWatermark()->setText($this->parseText($this->settings['text']['text']));
			}

			$sizes = $meta['sizes'];
			$sizes['full'] = array(
				'file' => $meta['file'],
				'mime-type' => $post->post_mime_type
			);

			$allowedSizes = $this->settings['general']['image_sizes'];

			$return = true;
			foreach($sizes as $size => $img){
				if(in_array($size, $allowedSizes)){
					$imgFile = str_replace($filebasename, wp_basename($img['file']), $filepath);
					if(!$this->watermark_single_image($imgFile, $img['mime-type']))
						$return = false;
				}
			}

			if($return){
				// mark attachment as watermarked
				update_post_meta($post->ID, '_ew_watermarked', '1');
			}

			return $return;
		}

		$this->error = 99;

		return false;
	}

	/**
 	 * Saves copy of the original image to allow to restore it
	 *
	 * @param  string file path
	 * @return void
	 */
	private function backup_attachment($filepath, $filename){
		$upload_dir = wp_upload_dir();
		$uploads = $upload_dir['basedir'];

		$subdir = str_replace(array($uploads, $filename), '', $filepath);

		$backup_dir = WP_CONTENT_DIR . EWDS . 'ew_backup' . $subdir;
		$new_file = $backup_dir . $filename;

		if(!file_exists($new_file)){

			if(!is_dir($backup_dir)){
				mkdir($backup_dir, 0755, true);
			}

			$result = copy($filepath, $new_file);

			if($result){
				return $new_file;
			}

			return false;
		}

		return true;
	}

	public function clean_backup($post_id){
		$file = get_post_meta($post_id, '_ew_backup_file', true);

		if(file_exists($file)){
			unlink($file);
		}
	}

	/**
 	 * Applies the watermark to the given image file
	 *
	 * @param  string path to image file
	 * @param  string image mime type
	 * @return boolean
	 */
	private function watermark_single_image($imageFile, $imageType){
		if($this->isGDEnabled()){

			$ew = $this->getEasyWatermark();
			$ew->setImagePath($imageFile)
				->setImageMime($imageType)
				->setOutputFile($imageFile)
				->setOutputMime($imageType);

			if(!$ew->create() || !$ew->saveOutput()){
				$error = $this->ewErrors[$ew->getError()];
				if($this->isBulkAction){
					$error = sprintf(__("Error: '%s', file: %s", 'easy-watermark'), $error, $imageFile);
				}
				$this->add_error($error);
				return false;
			}

			$ew->clean();
			return true;
		}

		return false;
	}

	/**
 	 * @var  object  stores EasyWatermark object
	 */
	private $ew;

	/**
 	 * Returns configured EasyWatermark object
	 *
	 * @return object
	 */
	public function getEasyWatermark(){
		if(!($this->ew instanceof EasyWatermark) && $this->isGDEnabled()){
			$imageSettings = $this->settings['image'];
			$textSettings = $this->getTextSettings();
			$this->ew = new EasyWatermark();
			$this->ew->setJpegQuality($this->settings['general']['jpg_quality']);

			$fontFile = EWBASE . EWDS . 'fonts' . EWDS . $textSettings['font'];
			if(file_exists($fontFile))
				$textSettings['font'] = $fontFile;

			$wType = $this->settings['general']['watermark_type'];
			if(!self::isFreeTypeEnabled()) {
				if($wType == 3) $wType = 1;
				elseif($wType == 2) $wType = 0;
			}
			if($wType == 1 || $wType == 3)
				$this->ew->imageSet($imageSettings);
			if($wType == 2 || $wType == 3)
				$this->ew->textSet($textSettings);
		}

		return $this->ew;
	}

	/**
 	 * Prints Easy Watermark page
	 *
	 * @return void
	 */
	public function easy_watermark(){
		if(isset($_GET['_wpnonce'])){
			global $wpdb, $wp_scripts;

			wp_enqueue_style('ew-tools', plugins_url() . '/easy-watermark/css/tools.css');
			wp_register_script('ewajax', plugin_dir_url(EWBASE . '/index.php') . 'js/ewajax.js', array('jquery', 'jquery-ui-progressbar'));

			wp_enqueue_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css');

			update_option('ew-bulk-page', 0);
			update_option('ew-bulk-counter', 0);

			if(isset($_GET['watermark_all']) && wp_verify_nonce($_GET['_wpnonce'], 'watermark_all')){
				$count = $wpdb->get_var("SELECT count(*) FROM $wpdb->posts
											WHERE post_type = 'attachment'
											AND post_mime_type LIKE 'image/%'");

				wp_localize_script('ewajax', 'ewData', array(
					'nonce' => wp_create_nonce('watermark_all_confirmed'),
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'total_items' => $count,
					'complete' => __('Complete!', 'easy-watermark')
				));

				wp_enqueue_script('watermark-all', plugin_dir_url(EWBASE . '/index.php') . 'js/watermark-all.js', array('ewajax'));

				update_option('ew-bulk-total', $count);

				$current_tool = 'watermark_all';
			}
			elseif(isset($_GET['restore_all']) && wp_verify_nonce($_GET['_wpnonce'], 'restore_all')){
				$count = $wpdb->get_var("SELECT count(*) FROM $wpdb->postmeta
											WHERE meta_key = '_ew_backup_file'");

				wp_localize_script('ewajax', 'ewData', array(
					'nonce' => wp_create_nonce('restore_all_confirmed'),
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'total_items' => $count,
					'complete' => __('Complete!', 'easy-watermark')
				));

				wp_enqueue_script('restore-all', plugin_dir_url(EWBASE . '/index.php') . 'js/restore-all.js', array('ewajax'));

				update_option('ew-bulk-total', $count);

				$current_tool = 'restore_all';
			}
		}

		include EWVIEWS . EWDS . 'easy-watermark-page.php';
	}

	/**
 	 * Prints style for admin page
	 *
	 * @return void
	 */
	public function easy_watermark_style(){
		if(get_current_screen()->id == 'media_page_easy-watermark') {
echo '<style type="text/css" media="screen">
#icon-easy-watermark {background: url('.plugins_url().'/'.self::$pluginSlug.'/images/icon-32.png) no-repeat 2px 0;}
</style>';
		}
	}

	/**
 	 * Marks image as watermarked or not
	 *
	 * @return void
	 */
	private function mark_image(){
		$id = (int) $_GET['attachment_id'];
		$mark = (int) $_GET['mark'];
		$page = $_GET['r'];

		update_post_meta($id, '_ew_watermarked', $mark);

		$url = $page == 'library' ? admin_url('upload.php?marked='.$mark) : admin_url('post.php?post='.$id.'&action=edit&marked='.$mark);

		wp_redirect($url);
		exit;
	}

	/**
 	 * Restores original image
	 *
	 * @return void
	 */
	private function restore_single(){
		$id = (int) $_GET['attachment_id'];
		$page = $_GET['r'];

		$this->restore_image($id);

		$url = $page == 'library' ? admin_url('upload.php?restored=1') : admin_url('post.php?post='.$id.'&action=edit&restored=1');

		wp_redirect($url);
		exit;
	}

	/**
 	 * Restores original images for bulk action ajax request
	 *
	 * @return void
	 */
	public function wp_ajax_restore_all(){
		check_ajax_referer('restore_all_confirmed', '_ewnonce');

		global $wpdb;

		$page = get_option('ew-bulk-page', 0);
		$count = get_option('ew-bulk-counter', 0);
		$total = get_option('ew-bulk-total', 0);

		$limit = 10;
		$offset = $page * $limit;

		$items = $wpdb->get_results($wpdb->prepare("
			SELECT * from $wpdb->postmeta where meta_key = '_ew_backup_file'
			order by meta_id ASC LIMIT %d
		", $limit, $offset));

		$output = array();
		foreach($items as $item){
			$this->restore_image($item->post_id);
			$output[] = sprintf(__('Image succesfully restored: %s'), $item->meta_value);
		}

		$output = '<p>' . implode('<br/>', $output) . '</p>';

		$count += $limit;
		if($count > $total) $count = $total;

		update_option('ew-bulk-page', $page + 1);
		update_option('ew-bulk-counter', $count);

		echo json_encode(array(
			'progress' => get_option('ew-bulk-counter'),
			'message' => $output
		));

		exit;
	}

	/**
 	 * Restores original image
	 *
	 * @param  integer  attachment id
	 * @return void
	 */
	private function restore_image($id){

		$backup_file = get_post_meta($id, '_ew_backup_file', true);
		$current_file = get_attached_file($id);
		$filebasename = wp_basename($current_file);

		// Restore main image file
		$r = rename($backup_file, $current_file);

		// Regenerate thumbnails
		$meta = get_post_meta($id, '_wp_attachment_metadata', true);

		foreach($meta['sizes'] as $size => $img){
			$file = str_replace($filebasename, wp_basename($img['file']), $current_file);
			unlink($file);
		}

		// Set flag to not watermark restored image
		$this->restoring = true;

		$metadata = wp_generate_attachment_metadata($id, $current_file);
		if(is_wp_error($metadata)){
			throw new Exception($metadata->get_error_message());
        }
		if(empty($metadata)) {
			throw new Exception(__('Unknown failure reason.', 'easy-watermark'));
        }
		wp_update_attachment_metadata($id, $metadata);

		// remove post metadata
		delete_post_meta($id, '_ew_backup_file');
		delete_post_meta($id, '_ew_watermarked');

		$this->restoring = false;
	}

	/**
 	 * Adds Easy Watermark column in media library
	 *
	 * @param  array
	 * @return array
	 */
	public function add_media_column($columns){

		$columns['ew-status'] = 'Easy Watermark';

		return $columns;
	}

	/**
 	 * Prints the content of Easy Watermark custom column
	 *
	 * @param  array
	 * @return array
	 */
	public function manage_media_custom_column($column_name, $post_id){
		if($column_name == 'ew-status'){
			$this->display_column_and_metabox($post_id);
		}
	}

	public function add_meta_boxes(){
		add_meta_box(
			'easy_watermark',
			__( 'Easy Watermark', 'easy-watermark' ),
			array($this, 'media_metabox'),
			'attachment', 'side', 'core'
        );
	}

	public function media_metabox(){
		global $post;

		$this->display_column_and_metabox($post->ID, 'post');
	}

	private function display_column_and_metabox($post_id, $page = 'library'){
		if($post_id != $this->settings['image']['watermark_id']){
			$watermarked = get_post_meta($post_id, '_ew_watermarked', true);
			$backup_file = get_post_meta($post_id, '_ew_backup_file', null);

			$status = $watermarked == '1' ? __('watermarked', 'easy-watermark') : __('not watermarked', 'easy-watermark');
			echo __('Status', 'easy-watermark') . ': <strong>' . $status . '</strong><br/>';

			global $post;
			if(current_user_can('edit_others_posts') || $post->post_author == wp_get_current_user()->ID){
				$mark = $watermarked == '1' ? '0' : '1';

				$link_text = $watermarked == '1' ?
					__('mark as not watermarked', 'easy-watermark') : __('mark as watermarked', 'easy-watermark');

				if($page == 'post'){
					$class = ' class="button-secondary"';
				}
				else {
					$class = null;
				}

				echo '<a href="'.wp_nonce_url(admin_url('upload.php?page=easy-watermark&attachment_id='.$post_id.'&r='.$page.'&mark='.$mark), 'ew_mark').'">' . $link_text . '</a><br/><br/><p><strong><a href="' . wp_nonce_url(admin_url('upload.php?page=easy-watermark&attachment_id='.$post_id.'&r='.$page), 'ew_add_watermark') . '"'.$class.'>'.__('Add Watermark', 'easy-watermark').'</a></strong></p>';

				if(!empty($backup_file)){
					// We can restore original image

				echo '<p><a href="' . wp_nonce_url(admin_url('upload.php?page=easy-watermark&attachment_id='.$post_id.'&r='.$page), 'ew_restore') . '"'.$class.'>'.__('Restore original image', 'easy-watermark').'</a></p>';
				}
			}
		}

		else {
			echo __('This image is used as watermark.', 'easy-watermark') . '<br/><a href="'.admin_url('options-general.php?page=easy-watermark-settings&tab=image').'">' . __('Change settings', 'easy-watermark') . '</a>';

		}
	}

	/**
 	 * Method run when activating plugin
	 *
	 * @return void
	 */
	public static function install(){
		$version = get_option(self::$pluginSlug.'-version', false);
		if($version)
			return; // Do nothing, the plugin has been installed before

		$settings = EW_Settings::getDefaults();

		self::update_settings($settings);
	}

	/**
 	 * Method to write given settings array to the db
	 *
	 * @param  array
	 * @return void
	 */
	private static function update_settings($settings){
		foreach($settings as $sectionName => $section){
			update_option(self::$pluginSlug.'-settings-'.$sectionName, $section);
		}
		update_option(self::$pluginSlug.'-version', self::$version);
	}

	/**
 	 * Method run when removing plugin
	 *
	 * @return void
	 */
	public static function uninstall(){
		$settings = EW_Settings::getDefaults();

		foreach($settings as $sectionName => $section){
			delete_option(self::$pluginSlug.'-settings-'.$sectionName);
		}
		delete_option(self::$pluginSlug.'-version');
	}

	/**
 	 * Method run when plugin version stored in WP options
	 * is lower than current version.
	 *
	 * @param  string  previously installed version
	 * @return void
	 */
	protected static function upgrade($version){
		$defaults = EW_Settings::getDefaults();

		if(version_compare($version, '0.1.1', '>')){
			$settings['general'] = get_option(self::$pluginSlug.'-settings-general');
			$settings['image'] = get_option(self::$pluginSlug.'-settings-image');
			$settings['text'] = get_option(self::$pluginSlug.'-settings-text');
		}
		else {
			$oldSettings = get_option(self::$pluginSlug.'-settings');

			$imgTypes = array();
			foreach($oldSettings['image_types'] as $type){
				$imgTypes[] = $type;
			}

			$general = array(
				'auto_add' => $oldSettings['auto_add'],
				'image_types' => $imgTypes
			);

			switch($version){
				case '0.1.1':
					$image = array(
						'watermark_url' => $oldSettings['image']['url'],
						'watermark_id' => $oldSettings['image']['id'],
						'watermark_path' => $oldSettings['image']['path'],
						'watermark_mime' => $oldSettings['image']['mime'],
						'position_x' => $oldSettings['image']['position_x'],
						'position_y' => $oldSettings['image']['position_y'],
						'offset_x' => $oldSettings['image']['offset_x'],
						'offset_y' => $oldSettings['image']['offset_y'],
						'opacity' => $oldSettings['image']['opacity']
					);
					break;
				default:
					$image = array(
						'watermark_url' => $oldSettings['image']['url'],
						'watermark_id' => $oldSettings['image']['id'],
						'watermark_path' => $oldSettings['image']['path'],
						'watermark_mime' => $oldSettings['image']['mime'],
						'position_x' => $oldSettings['image']['position-horizontal'],
						'position_y' => $oldSettings['image']['position-vert'],
						'offset_x' => $oldSettings['image']['offset-horizontal'],
						'offset_y' => $oldSettings['image']['offset-vert'],
						'opacity' => $oldSettings['image']['alpha']
					);
					break;
			}

			$settings = array(
				'general' => $general,
				'image' => $image,
				'text' => array()
			);
			delete_option(self::$pluginSlug.'-settings');
		}

		if(version_compare($version, '0.2.2', '<')){
			$settings['image']['alignment'] = self::getAlignment($settings['image']['position_x'], $settings['image']['position_y']);
			$settings['text']['alignment'] = self::getAlignment($settings['text']['position_x'], $settings['text']['position_y']);
		}

		$settings['general'] = array_merge($defaults['general'], $settings['general']);
		$settings['image'] = array_merge($defaults['image'], $settings['image']);
		$settings['text'] = array_merge($defaults['text'], $settings['text']);

		self::update_settings($settings);
	}

	private function add_error($msg){
		$this->notices[] = array('error', $msg);
	}

	private function add_info($msg){
		$this->notices[] = array('update', $msg);
	}

	/**
 	 * Computes alignment number based on position_x and position_y
	 *
	 * @param  int
	 * @param  int
	 * @return int
	 */
	private static function getAlignment($x, $y){
		$a = false;
		switch($y){
			case 'top':
				switch($x){
					case 'lft':
					case 'left':
						$a = 1;
						break;
					case 'ctr':
					case 'center':
						$a = 2;
						break;
					case 'rgt':
					case 'right':
						$a = 3;
						break;
				}
				break;
			case 'mdl':
			case 'middle':
				switch($x){
					case 'lft':
					case 'left':
						$a = 4;
						break;
					case 'ctr':
					case 'center':
						$a = 5;
						break;
					case 'rgt':
					case 'right':
						$a = 6;
						break;
				}
				break;
			case 'btm':
			case 'bottom':
				switch($x){
					case 'lft':
					case 'left':
						$a = 7;
						break;
					case 'ctr':
					case 'center':
						$a = 8;
						break;
					case 'rgt':
					case 'right':
						$a = 9;
						break;
				}
				break;
		}

		return $a;
	}

	/**
 	 * Sets settings array.
	 *
	 * @return void
	 */
	public function setSettings($settings){
		$this->settings = $settings;
	}

	public function checkRolePermission($user_id = null){
		if(is_numeric($user_id)){
			$user = get_userdata($user_id);
		}
		else {
	        $user = wp_get_current_user();
		}

		$result = false;

		$roles = $this->getAllowedRoles();

		if(!empty($user)){
			if(is_string($user->roles) && isset($roles[$user->roles]) && ((int) $roles[$user->roles] == 1))
				$result = true;
			else
				foreach($roles as $role => $allowed){
					if(in_array($role, $user->roles) && ((int) $allowed == 1)){
						$result = true;
						break;
					}
				}
		}

		return $result;
	}

	private function checkPostType(){
		if(isset($_REQUEST['post_id']) && $_REQUEST['post_id'] != 0){
			$post_id = (int) $_REQUEST['post_id'];
			$post_type = get_post_type($post_id);
		}
		else {
			// unattached image
			$post_type = 'unattached';
		}

		$allowed_post_types = $this->settings['general']['allowed_post_types'];

		if($post_type != null){
			if(in_array($post_type, $allowed_post_types))
				return true;
		}

		return false;
	}

	private function getAllowedRoles(){
		$roles = $this->settings['general']['allowed_roles'];

		$roles['administrator'] = 1;

		return $roles;
	}

	private function getTextSettings(){
		$settings = $this->settings['text'];

		$settings['text'] = $this->parseText($settings['text']);

		return $settings;
	}

	private function parseText($text){
		$user = wp_get_current_user();
		$date = !empty($this->settings['general']['date_format']) ? $this->settings['general']['date_format'] : get_option('date_format');
		$time = !empty($this->settings['general']['time_format']) ? $this->settings['general']['time_format'] : get_option('time_format');

		if(is_object($this->currentImage)){
			$author = get_user_by('id', $this->currentImage->post_author);
			$imageTitle = $this->currentImage->post_title;
			$imageAlt = get_post_meta($this->currentImage->ID, '_wp_attachment_image_alt', true);
		}
		else {
			$author = $user;
			$imageTitle = '(image title here)';
			$imageAlt = '(image alt here)';
		}

		$placeholders = array(
			'%user%',
			'%user_name%',
			'%user_email%',
			'%user_url%',
			'%author%',
			'%author_name%',
			'%author_email%',
			'%author_url%',
			'%admin_email%',
			'%blog_name%',
			'%blog_url%',
			'%date%',
			'%time%',
			'%image_title%',
			'%image_alt%',
		);

		$replacements = array(
			$user->user_login,
			$user->display_name,
			$user->user_email,
			$user->user_url,
			$author->user_login,
			$author->display_name,
			$author->user_email,
			$author->user_url,
			get_bloginfo('admin_email'),
			get_bloginfo('name'),
			home_url(),
			date($date),
			date($time),
			$imageTitle,
			$imageAlt
		);

		return str_replace($placeholders, $replacements, $text);
	}

	public function getRoles(){
		$allRoles = get_editable_roles();

		$roles = array();
		foreach($allRoles as $role => $details){
			if($role == 'administrator')
				continue;

			if(isset($details['capabilities']['upload_files']) && $details['capabilities']['upload_files'] == true){
				$roles[$role] = $details['name'];
			}
		}

		return $roles;
	}

	public function getPostTypes($result = null){
		$args = array(
			'public' => true,
			'_builtin' => false
		);

		$post_types = get_post_types($args, $result);

		return $post_types;
	}
}
