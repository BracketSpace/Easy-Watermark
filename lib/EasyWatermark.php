<?php
/**
 * @package	Easy Watermark
 * @version	3.3
 * @license	GPL
 * @author	Wojtek Szałkiewicz
 * @author url 	http://szalkiewicz.pl
 * @email	wojtek@szalkiewicz.pl
 *
 * EasyWatermark can simply add watermark to any image using GD extension.
 * It supports gif and png with alpha chanel and jpg formats.
 * Watermark location can be simply defined with position (top, middle, bottom, left, center, right)
 * and offset (in pixels or percentage) counted from given position
 * (e.g. position_x = center, offset_x = -200; so it's 200 pixels left from centered position).
 * Mime types (see: setImageMime, setWatermarkMime, setOutputMime) can be only file formats (e.g. 'png')
 * or full mime types ('image/jpeg') like in wordpress plugin (http://wordpress.org/extend/plugins/easy-watermark)
 * where mime types are taken directly from WP database.
 *
 * Examples:
 *	$ew = new EasyWatermark(array(
 *		'position_x' => 'left',		// can be: left, lft, center, ctr, right, rgt or numeric (respectively 1, 2, 3)
 *		'position_y' => 'bottom',	// can be: top, middle, mdl, bottom, btm (respectively 1, 2, 3)
 *		'offset_x' => 100,			// numeric value for pixels or string ('100px', '20%')
 *		'offset_y' => '10%',		// numeric value for pixels or string ('100px', '20%')
 *		'opacity' => 20				// percentage opacit (0 - non visible, 100 - fully visible)
 *	));
 *
 * 	$ew->setImagePath('some/path/to/img.jpg')
 * 		->setWatermarkPath('path/to/watermark.png')
 * 		->printOutput(); // returns image with proper header
 *
 * You can also specify output format, and save it as file using saveOutput method.
 */

class EasyWatermark
{
	// Error codes
	const ERROR_SAME_IMAGE_PATHS		= 1,
		  ERROR_NO_WATERMARK_SET		= 2,
		  ERROR_NO_INPUT_IMAGE			= 3,
		  ERROR_NOT_ALLOWED_TYPE		= 4,
		  ERROR_NO_OUTPUT_FILE_SET		= 5,
		  ERROR_NOT_ALLOWED_OUTPUT_TYPE = 6,
		  ERROR_UNKNOWN					= 9;

	/**
	 * @var array  default settings
	 */
	private $defaultSettings = array(
			'image' => array(
				'position_x' => 2,
				'position_y' => 2,
				'offset_x' => 0,
				'offset_y' => 0,
				'opacity' => 100,	// percent
				'scale_mode' => 'none',	// none, fill, fit, fit_to_width, fit_to_height
				'scale' => 100,		// percent, used with fit_to_width and fit_to_height
				'scale_to_smaller' => false
			),
			'text' => array(
				'position_x' => 2,
				'position_y' => 2,
				'offset_x' => 0,
				'offset_y' => 0,
				'opacity' => 60,	// percent
				'color' => '000000',
				'font' => '',
				'size' => 24,
				'angle' => 0,
				'text' => ''
			)
		);

	/**
	 * @var integer  jpg quality
	 */
	private $jpegQuality = 75;

	/**
	 * @var array  settings
	 */
	private $settings = array();

	/**
	 * @var string  path to image file
	 */
	private $imagePath = '';

	/**
	 * @var string  image mime type
	 */
	private $imageMime = '';

	/**
	 * @var string  path to watermark file
	 */
	private $watermarkPath = '';

	/**
	 * @var string  watermark mime type
	 */
	private $watermarkMime = '';

	/**
	 * @var string  output file path
	 */
	private $outputFile = '';

	/**
	 * @var string  output file mime type
	 */
	private $outputMime = '';

	/**
	 * @var string  watermark text
	 */
	private $text = '';

	/**
	 * @var string  error message
	 */
	private $error = '';

	/**
	 * Creates EasyWatermark object
	 *
	 * @param  array  settings
	 */
	public function __construct($imageSettings = array(), $textSettings = array()){
		$this->imageSet($imageSettings);
		$this->textSet($textSettings);
	}

	/**
	 * Sets jpg quality
	 *
	 * @param  int
	 * @return void
	 */
	public function setJpegQuality($quality){
		$this->jpegQuality = $quality;
	}

	/**
	 * Sets watermark image parameters
	 *
	 * @see    $defaultSettings
	 * @chainable
	 * @param  string  settings key
	 * @param  string  value
	 * @return object  $this
	 */
	public function imageSet($key, $val = null){
		if(is_array($key)){
			// act recursive
			foreach($key as $k => $v){
				$this->imageSet($k, $v);
			}
			return $this;
		}

		switch($key){
			case 'image_path':
				$this->setImagePath($val);
				break;
			case 'image_mime':
				$this->setImageMime($val);
				break;
			case 'watermark_path':
				$this->setWatermarkPath($val);
				break;
			case 'watermark_mime':
				$this->setWatermarkMime($val);
				break;
			case 'output_file':
				$this->setOutputFile($val);
				break;
			case 'output_mime':
				$this->setOutputMime($val);
				break;
			default:
				$this->settings['image'][$key] = $val;
				break;
		}

		return $this;
	}

	/**
	 * Sets watermark text parameters
	 *
	 * @see    $defaultSettings
	 * @chainable
	 * @param  string  settings key
	 * @param  string  value
	 * @return object  $this
	 */
	public function textSet($key, $val = null){
		if(is_array($key)){
			// act recursive
			foreach($key as $k => $v){
				$this->textSet($k, $v);
			}
			return $this;
		}

		if($key == 'text'){
			$this->setText($val);
		}
		else {
			$this->settings['text'][$key] = $val;
		}

		return $this;
	}

	/**
	 * Sets watermark text
	 *
	 * @chainable
	 * @param  string  watermark text
	 * @return object  $this
	 */
	public function setText($value){
		$this->text = $value;

		return $this;
	}

	/**
	 * Sets input image path
	 *
	 * @chainable
	 * @param  string  image path
	 * @return object  $this
	 */
	public function setImagePath($path){
		$this->imagePath = $path;

		return $this;
	}

	/**
	 * Sets input image mime type
	 *
	 * @chainable
	 * @param  string  image mime
	 * @return object  $this
	 */
	public function setImageMime($mime){
		$this->imageMime = $mime;

		return $this;
	}

	/**
	 * Sets watermark path
	 *
	 * @chainable
	 * @param  string  watermark path
	 * @return object  $this
	 */
	public function setWatermarkPath($path){
		$this->watermarkPath = $path;

		return $this;
	}

	/**
	 * Sets watermark mime type
	 *
	 * @chainable
	 * @param  string  watermark mime
	 * @return object  $this
	 */
	public function setWatermarkMime($mime){
		$this->watermarkMime = $mime;

		return $this;
	}

	/**
	 * Sets output file path
	 *
	 * @chainable
	 * @param  string  output file path
	 * @return object  $this
	 */
	public function setOutputFile($path){
		$this->outputFile = $path;

		return $this;
	}

	/**
	 * Sets output file mime type
	 *
	 * @chainable
	 * @param  string  output mime
	 * @return object  $this
	 */
	public function setOutputMime($mime){
		$this->outputMime = $mime;

		return $this;
	}

	/**
	 * Checks if error occurred
	 *
	 * @return boolean
	 */
	public function hasError(){
		return !empty($this->error);
	}

	/**
	 * Gets error message
	 *
	 * @return string  error message
	 */
	public function getError(){
		return $this->error;
	}

	/**
	 * Merges settings with defaults,
	 * parses offset values to determine if they are pixels or percentage
	 *
	 * @return  array  settings
	 */
	private function parseSettings($section){

		if(!isset($this->defaultSettings[$section]))
			return false;

		$settings = isset($this->settings[$section]) ? 
				array_merge($this->defaultSettings[$section], $this->settings[$section]) :
				$this->defaultSettings[$section];

		if(strpos($settings['offset_x'], '%') === (strlen($settings['offset_x']) - 1)){
			$settings['offset_x'] = substr($settings['offset_x'], 0, strlen($settings['offset_x']) - 1);
			$settings['offset_x_pc'] = true;
		}
		else {
			if(strpos($settings['offset_x'], 'px') === (strlen($settings['offset_x']) - 2)){
				$settings['offset_x'] = substr($settings['offset_x'], 0, strlen($settings['offset_x']) - 2);
			}
			$settings['offset_x_pc'] = false;
		}

		if(strpos($settings['offset_y'], '%') === strlen($settings['offset_y']) - 1){
			$settings['offset_y'] = substr($settings['offset_y'], 0, strlen($settings['offset_y']) - 1);
			$settings['offset_y_pc'] = true;
		}
		else {
			if(strpos($settings['offset_y'], 'px') === strlen($settings['offset_x']) - 2){
				$settings['offset_y'] = substr($settings['offset_x'], 0, strlen($settings['offset_y']) - 2);
			}
			$settings['offset_y_pc'] = false;
		}

		switch($settings['position_x']){
			case 'left':
			case 'lft':
				$settings['position_x'] = 1;
				break;
			case 'center':
			case 'ctr':
				$settings['position_x'] = 2;
				break;
			case 'right':
			case 'rgt':
				$settings['position_x'] = 3;
				break;
		}

		switch($settings['position_y']){
			case 'top':
				$settings['position_y'] = 1;
				break;
			case 'middle':
			case 'mdl':
				$settings['position_y'] = 2;
				break;
			case 'bottom':
			case 'btm':
				$settings['position_y'] = 3;
				break;
		}

		switch($settings['position_y']){
			case 'top':
				$settings['position_y'] = 1;
				break;
			case 'middle':
			case 'mdl':
				$settings['position_y'] = 2;
				break;
			case 'bottom':
			case 'btm':
				$settings['position_y'] = 3;
				break;
		}

		if(isset($settings['color'])){
			if(strpos($settings['color'], '#') === 0){
				$settings['color'] = substr($settings['color'], 1);
			}
			if(strlen($settings['color']) == 3){
				$settings['color'] = $settings['color'][0].$settings['color'][0].
						$settings['color'][1].$settings['color'][1].
						$settings['color'][2].$settings['color'][2];
			}
		}

		return $settings;
	}

	/**
	 * @var  resource  GD image created from input file
	 */
	private $inputImage;

	/**
	 * @var  string  image type
	 */
	private $inputImageType;

	/**
	 * @var  array  image size
	 */
	private $imageSize;

	/**
	 * @var  resource  GD image created from watermark file
	 */
	private $watermarkImage;

	/**
	 * @var  string  image type
	 */
	private $watermarkImageType;

	/**
	 * @var  array  watermark size
	 */
	private $watermarkSize;

	/**
	 * @var  resource  GD image prepared as output
	 */
	private $outputImage;

	/**
	 * @var  array  allowed image types
	 */
	private $allowedTypes = array('jpeg', 'jpg', 'png', 'gif');

	/**
	 * Creates output image with watermark
	 *
	 * @chainable
	 * @param  string  input image path
	 * @param  string  input image mime
	 * @param  string  watermark path
	 * @param  string  watermark mime
	 * @param  string  output file path
	 * @param  string  output file mime
	 * @return boolean
	 */
	public function create(){

		$return = false;
		if(!empty($this->imagePath)){
			if(!empty($this->watermarkPath)){
				if($this->imagePath != $this->watermarkPath){
					$this->applyImageWatermark();
					$return = true;
				}
				else {
					$this->error = self::ERROR_SAME_IMAGE_PATHS;
				}
			}

			if(!empty($this->text)){
				$this->applyTextWatermark();
				$return = true;
			}

			if(!$return){
				$this->error = self::ERROR_NO_WATERMARK_SET;
			}
		}
		else {
			$this->error = self::ERROR_NO_INPUT_IMAGE;
		}

		return $return;
	}

	/**
	 * Creates output image with watermarm
	 *
	 * @return boolean
	 */
	public function applyImageWatermark(){

		$this->getOutputImage();

		if(!$this->outputImage)
			return false;

		// Create GD image resources
		list($this->watermarkImage, $this->watermarkImageType) = $this->createImage($this->watermarkPath, $this->watermarkMime);

		if(!$this->watermarkImage)
			return false;

		// Prepare settings array
		$settings = $this->parseSettings('image');

		// Get image dimensions
		list($imageWidth, $imageHeight) = $this->getImageSize();

		// Get watermark dimensions
		list($watermarkWidth, $watermarkHeight) = $this->getWatermarkSize();

		if($settings['scale_mode'] == 'fill' || $settings['scale_mode'] == 'fit'){
			$imgRatio = $imageWidth / $imageHeight;
			$watermarkRatio = $watermarkWidth / $watermarkHeight;

			if(($settings['scale_mode'] == 'fill' && $watermarkRatio < $imgRatio) ||
				($settings['scale_mode'] == 'fit' && $watermarkRatio > $imgRatio)){
				$settings['scale_mode'] = 'fit_to_width';
				$settings['scale'] = 100;
			}
			else {
				$settings['scale_mode'] = 'fit_to_height';
				$settings['scale'] = 100;
			}
		}

		if($settings['scale_mode'] == 'fit_to_width' && (!$settings['scale_to_smaller'] || $imageWidth < $watermarkWidth)){
			$scale = $imageWidth / $watermarkWidth;
			$newWidth = $imageWidth * $settings['scale'] / 100;
			$newHeight = $watermarkHeight * $scale * $settings['scale'] / 100;
		}
		elseif($settings['scale_mode'] == 'fit_to_height' && (!$settings['scale_to_smaller'] || $imageHeight < $watermarkHeight)){
			$scale = $imageHeight / $watermarkHeight;
			$newHeight = $imageHeight * $settings['scale'] / 100;
			$newWidth = $watermarkWidth * $scale * $settings['scale'] / 100;
		}

		if(isset($newWidth)){
			$tmpImage = imagecreatetruecolor($newWidth, $newHeight);
			if(($this->watermarkImageType == 'png' && $this->isAlphaPng($this->watermarkPath))){
				// preserve png transparency
//				imagecolortransparent($tmpImage, imagecolorallocatealpha($tmpImage, 0, 0, 0, 127));
				imagealphablending($tmpImage, false);
				imagesavealpha($tmpImage, true);
			}

			imagecopyresampled($tmpImage, $this->watermarkImage,
				0, 0, 0, 0,
				$newWidth, $newHeight, $watermarkWidth, $watermarkHeight
			);

			// Clean memory
			imagedestroy($this->watermarkImage);

			$this->watermarkImage = $tmpImage;
			$watermarkWidth = $newWidth;
			$watermarkHeight = $newHeight;
			unset($tmpImage, $newWidth, $nweHeight);
		}

		// Compute watermark offset
		$offsetX = $this->computeOffset($settings['position_x'], $settings['offset_x'],
						$imageWidth, $watermarkWidth, $settings['offset_x_pc']);
		$offsetY = $this->computeOffset($settings['position_y'], $settings['offset_y'],
						$imageHeight, $watermarkHeight, $settings['offset_y_pc']);

		// Prepare params for copying function
		$params = array($this->outputImage, $this->watermarkImage,
			$offsetX, $offsetY,   
			0, 0,
			$watermarkWidth, $watermarkHeight
		);
		if($this->watermarkImageType == 'png' && $this->isAlphaPng($this->watermarkPath)){
			// Watermark is PNG with alpha channel, use imagecopy
			$func = 'imagecopy';
		}
		else {
			// Use imagecopymerge with opacity param for other images
			$func = 'imagecopymerge';
			$params[] = $settings['opacity'];
		}

		// Copy watermark to output image
		call_user_func_array($func, $params);

		return true;
	}

	/**
	 * Adds text watermark to output image
	 *
	 * @return boolean
	 */
	public function applyTextWatermark(){

		$this->getOutputImage();

		if(!$this->outputImage)
			return false;

		$settings = $this->parseSettings('text');

		list($textWidth, $textHeight, $deltaX, $deltaY) = $this->getTextSize($settings['size'], $settings['angle'], $settings['font'], $this->text);
		list($imageWidth, $imageHeight) = $this->getImageSize();

		$color = $settings['color'];
		$color = imagecolorallocatealpha($this->outputImage,
				hexdec(substr($color, 0, 2)),
				hexdec(substr($color, 2, 2)),
				hexdec(substr($color, 4, 2)),
				127 * (100 - $settings['opacity']) / 100);

		// Compute watermark offset
		$offsetX = $this->computeOffset($settings['position_x'], $settings['offset_x'],
						$imageWidth, $textWidth, $settings['offset_x_pc']);
		$offsetY = $this->computeOffset($settings['position_y'], $settings['offset_y'],
						$imageHeight, $textHeight, $settings['offset_y_pc']);

		imagettftext($this->outputImage,
			$settings['size'],
			$settings['angle'],
			$offsetX - $deltaX, $offsetY - $deltaY,
			$color,
			$settings['font'],
			$this->text);

		return true;
	}

	/**
	 * prints text preview
	 *
	 * @return boolean
	 */
	public function printTextPreview(){
		$settings = $this->settings['text'];
		list($width, $height, $deltaX, $deltaY) = $this->getTextSize($settings['size'], $settings['angle'], $settings['font'], $this->text);

		$this->outputImage = imagecreatetruecolor($width, $height);

		if($this->outputImage){
			$grid = 6;
			$grey = imagecolorallocate($this->outputImage, 130,130,130);
			$white = imagecolorallocate($this->outputImage, 200,200,200);
			$startColor = 1;
			for($i = 0; $i < ($height / $grid); $i++){
				$currentColor = $startColor;
				for($n = 0; $n < ($width / $grid); $n++){
					$color = ($currentColor == 1) ? $grey : $white;
					imagefilledrectangle($this->outputImage, $grid * $n, $grid * $i, $grid * $n + $grid, $grid * $i + $grid, $color);
					$currentColor = ($currentColor == 1) ? 0 : 1;
				}
				$startColor = ($startColor == 1) ? 0 : 1;
			}

			$this->settings['text']['position_x'] = 1;
			$this->settings['text']['position_y'] = 1;
			$this->settings['text']['offset_x'] = 0;
			$this->settings['text']['offset_y'] = 0;
			$this->outputMime = 'jpeg';

			$this->applyTextWatermark();

			return $this->printOutput();
		}

		$this->error = self::ERROR_UNKNOWN;

		return false;
	}

	/**
	 * Returns size of text bounding box width x and y distance from font baseline
	 *
	 * @param  integer  font size
	 * @param  integer  angle
	 * @param  string   font file path
	 * @param  string   text
	 * @return array
	 */
	private function getTextSize($fontSize, $angle, $font, $text){

		$bb = imagettfbbox($fontSize, $angle, $font, $text);

		$maxX = max($bb[0], $bb[2], $bb[4], $bb[6]);
		$minX = min($bb[0], $bb[2], $bb[4], $bb[6]);
		$x = $maxX - $minX;

		$maxY = max($bb[1], $bb[3], $bb[5], $bb[7]);
		$minY = min($bb[1], $bb[3], $bb[5], $bb[7]);
		$y = $maxY - $minY;

		return array($x, $y, $minX, $minY);
	}

	/**
	 * Creates if not exist and returns input image
	 *
	 * @return resource  GD image
	 */
	private function getInputImage(){
		if(empty($this->inputImage)){
			list($this->inputImage, $this->inputImageType) = $this->createImage($this->imagePath, $this->imageMime);
		}

		return $this->inputImage;
	}

	/**
	 * Creates if not exist and returns output image
	 *
	 * @return resource  GD image
	 */
	private function getOutputImage(){
		if(empty($this->outputImage)){
			if(empty($this->inputImage)){
				// Create Input Image
				$this->getInputImage();
			}

			if(!$this->inputImage){
				$this->outputImage = false;
			}
			else {
				list($imageWidth, $imageHeight) = $this->getImageSize();

				// Create blank image
				$this->outputImage = imagecreatetruecolor($imageWidth, $imageHeight);

				$this->outputMime = !empty($this->outputMime) ? $this->detectMimeType($this->outputMime) : $this->inputImageType;

				if(($this->outputMime == 'png' && $this->isAlphaPng($this->imagePath))){
					// Preserve opacity for png images
					imagealphablending($this->outputImage, false);
					imagesavealpha($this->outputImage, true);
				}

				imagecopy($this->outputImage, $this->inputImage,
					0, 0, 0, 0, $imageWidth, $imageHeight
				);

				// Destroy Input Image to save memory
				imagedestroy($this->inputImage);
				unset($this->inputImage);
			}
		}

		if($this->outputImage)
			imagealphablending($this->outputImage, true);

		return $this->outputImage;
	}

	/**
	 * Returns input image size
	 *
	 * @return array  width and height
	 */
	private function getImageSize(){
		if(empty($this->imageSize)){
			$img = is_resource($this->inputImage) ? $this->inputImage : $this->outputImage;
			$this->imageSize = array(
				imagesx($img),
				imagesy($img)
			);
		}

		return $this->imageSize;
	}

	/**
	 * Returns watermark image size
	 *
	 * @return array  width and height
	 */
	private function getWatermarkSize(){
		if(empty($this->watermarkSize)){
			$this->watermarkSize = array(
				imagesx($this->watermarkImage),
				imagesy($this->watermarkImage)
			);
		}

		return $this->watermarkSize;
	}

	/**
	 * Computes offset 
	 *
	 * @param  numeric  position (1 - left/top, 2 - center/middle, 3 - right/bottom)
	 * @param  numeric  offset
	 * @param  numeric  image dimension (width or height)
	 * @param  numeric  watermark dimension (width or height)
	 * @param  boolean  is offset percentage?
	 * @return numeric  computed offset
	 */
	private function computeOffset($position, $offset, $imgDim, $wDim, $pc){
		if($pc){
			// Percentage offset
			$offset = round(($offset / 100) * $imgDim);
		}
		switch($position){
			case 1:
				break;
			case 2:
				$offset = (($imgDim - $wDim) / 2) + $offset;
				break;
			case 3:
				$offset = $imgDim - $wDim - $offset;
				break;
		}

		return (int) $offset;
	}

	/**
	 * Creates GD image based on its type
	 *
	 * @param  string path to file
	 * @param  string mime type
	 * @return string mime type
	 */
	private function createImage($filePath, $mime){
		$type = $this->detectMimeType($mime, $filePath);

		if(!in_array($type, $this->allowedTypes)){
			$this->error = self::ERROR_NOT_ALLOWED_TYPE;
			return false;
		}

		if($type == 'jpg') $type = 'jpeg';
		$func = 'imagecreatefrom' . $type;

		$image = $func($filePath);
		if($type == 'png' && $this->isAlphaPng($filePath)){
			imagealphablending($image, false);
			imagesavealpha($image, true);
		}

		return array($image, $type);
	}

	/**
	 * Prints created image directly to the browser
	 *
	 * @return boolean
	 */
	public function printOutput(){
		return $this->prepareOutput();
	}

	/**
	 * Saves created image
	 *
	 * @param  string  filename (required if not set earlier)
	 * @return boolean
	 */
	public function saveOutput($file = null){
		if(empty($file)) $file = $this->outputFile;

		if(empty($file)){
			$this->error = self::ERROR_NO_OUTPUT_FILE_SET;
			return false;
		}

		return $this->prepareOutput($file);
	}

	/**
	 * Saves or outputs created image depending on $output param
	 *
	 * @param  string  output file
	 * @return boolean
	 */
	private function prepareOutput($output = null){
		$type = !empty($this->outputMime) ? $this->detectMimeType($this->outputMime) : $this->inputImageType;

		if(!$type || !in_array($type, $this->allowedTypes)){
			$this->error = self::ERROR_NOT_ALLOWED_OUTPUT_TYPE;
			return false;
		}

		if($output === null)
			// Return image directly to the browser
			header('Content-Type: image/'.$type);

		$params = array(
			$this->outputImage, 
			$output
		);

		if($type == 'jpg') $type = 'jpeg';

		if($type == 'jpeg'){
			$params[] = $this->jpegQuality;
		}

		$func = 'image'.$type;

		$result = @call_user_func_array($func, $params);

		$this->clean();

		if(!$result){
			$this->error = self::ERROR_UNKNOWN;
			return false;
		}

		return true;
	}

	/**
	 * Detects mime type using php finfo object and extracts what's needed
	 *
	 * @param  string path to file
	 * @param  string mime type
	 * @return string mime type
	 */
	private function detectMimeType($mime, $filePath = null){
		if(empty($mime) && $filePath){
			// Get finfo object to detect mime types
			$finfo = $this->getFinfo();

			$mime = @$finfo->file($filePath);
		}
		if(!$mime)
			return false;

		if(strpos($mime, 'image/') === 0)
			$mime = substr($mime, 6);

		return $mime;
	}

	/**
	 * @var  object  finfo object
	 */
	private $finfo;

	/**
	 * Returns finfo object
	 *
	 * @return object
	 */
	private function getFinfo(){
		if(!($this->finfo instanceof finfo)){
			$this->finfo = $finfo = new finfo(FILEINFO_MIME_TYPE);
		}

		return $this->finfo;
	}

	/**
	 * Verifies if png file has alpha chanel
	 *
	 * @param  string  path to png file
	 * @return boolean
	 */
	public function isAlphaPng($file){
		/* color type of png image stored at 25 byte:
		 * 0 - greyscale
		 * 2 - RGB
		 * 3 - RGB with palette
		 * 4 - greyscale + alpha
		 * 6 - RGB + alpha
		 */
		$colorByte = ord(@file_get_contents($file, false, null, 25, 1));
		return ($colorByte == 6 || $colorByte == 4);
	}

	/**
	 * cleans up image resources when object is destructed
	 */
	public function clean(){
		if($this->watermarkImage)	imagedestroy($this->watermarkImage);	$this->watermarkImage = null;
		if($this->outputImage)		imagedestroy($this->outputImage);	$this->outputImage = null;
		$this->imageSize = null;
		$this->inputImage = null;
	}

	/**
	 * cleans up image resources when object is destructed
	 */
	public function __destruct(){
		$this->clean();
	}
}
