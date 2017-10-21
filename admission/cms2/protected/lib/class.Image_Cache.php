<?php

/**
 * Class to manipulate, store, and retrieve uploaded assets to/from a
 * cache structure
 */
class Image_Cache {

	protected $inputSourcePath = false;
	protected $sourcepath = false;
	protected $filename = false;
	protected $cachedir_base = false;
	protected $cachedir = false;
	protected $cachepath = false;
	protected $maxWidth = false;
	protected $maxHeight = false;
	protected $canvasWidth = '';
	protected $canvasHeight = '';
	protected $canvasBackgroundColor = "#ffffff";
	protected $maxArea = '';
	protected $crop = false;
	protected $forceCache = false;
	protected $quality = 100;				// use the jpeg 1-100 quality scale
	protected $imageResource = false;
	protected $errors = array();
	protected $greyscale = 0;
	protected $cacheProtected = false;
	protected $sourceProtected = false;
	protected $authorizedImages ;
	
	protected $params = array(
		"maxWidth" => false,
		"maxHeight" => false,
		"canvasWidth" => false,
		"canvasHeight" => false,
		"maxArea" => false,
		"canvasBackgroundColor" => "#ffffff",
		"crop" => false,
		"quality" => 50,
		"greyscale" => 0,
		'forceCache' => false,
		'outputFormat' => 'jpg'
	);

	
	public function authorizeProtectedImage() {
		if (! $this->sourceProtected)
			return;
		$sourcePath = trim($this->getSourcePath());

		if (empty($sourcePath))
			return;
		
		if (! $this->protectedImageIsAuthorized()) {
			$this->authorizedImages[] = $sourcePath;
		}
	}
	
	public function protectedImageIsAuthorized() {
		$sourcePath = trim($this->getSourcePath());
		return in_array($sourcePath, $this->authorizedImages);
	}
	
	
	/**
	 * Return a directory name comprised of the image parameters for use in the cache path
	 * @return	String		
	 */
	public function resolveCacheSubdirFromParams() {

		if ($this->getParam('maxWidth') == '') {
			$maxWidth = "NONE";
		} else {
			$maxWidth = $this->getParam('maxWidth');
		}
		if ($this->getParam('maxHeight') == '') {
			$maxHeight = "NONE";
		} else {
			$maxHeight = $this->getParam('maxHeight');
		}
		if ($this->getParam('maxArea') == '') {
			$maxArea = "NONE";
		} else {
			$maxArea = $this->getParam('maxArea');
		}
		
		if ($this->getParam('crop') == false) {
			$crop = "NONE";
		} else {
			$crop = "YES";
		}
		
		if ($this->getParam('canvasWidth') == false) {
			$canvasWidth = "NONE";
		} else {
			$canvasWidth = $this->getParam('canvasWidth');
		}

		if ($this->getParam('canvasHeight') == false) {
			$canvasHeight = "NONE";
		} else {
			$canvasHeight = $this->getParam('canvasHeight');
		}
		
		if ($this->hasCanvas()) {
			$canvasBackgroundColor = str_replace("#", "", $this->getParam('canvasBackgroundColor'));
		} else {
			$canvasBackgroundColor = "NONE";
		}
		
		if ($this->getParam('greyscale') == false) {
			$greyscale = "NO";
		} else {
			$greyscale = "YES";
		}
		
		$quality = $this->getParam('quality');

		return $maxWidth . "_" . $maxHeight . "_" . $maxArea . "_" . $crop . "_" . $quality . "_" . $greyscale . "_" . $canvasWidth . "_" . $canvasHeight . "_" . $canvasBackgroundColor;
		
	}	
	
	/**
	 * Constructor
	 * @param	String	$sourcepath	Filesystem path to the image. May be relative or absolute.
	 * @param	Boolean	$greyscale	Whether or not this is a greyscale image. (deprecated)
	 * @return	Void
	 */
	public function __construct($sourcepath, $sourceProtected = false, $cacheProtected = false) {
		if ($sourceProtected) {
			session_start();
			if (! isset($_SESSION[APP_PATH]["authorizedImages"])) {
				$_SESSION[APP_PATH]["authorizedImages"] = array();
			}
			$this->authorizedImages =& $_SESSION[APP_PATH]["authorizedImages"];
		}
			
		// retain the original input sourcepath. it's the most reliable
		// way to re-construct the image.php version of the URL if we have to.
		$this->inputSourcePath = $sourcepath;

		$this->setSourceProtected($sourceProtected);
		$this->setCacheProtected($cacheProtected);

		$this->populateSourcePaths();
	}

	
	/**
	 * Sets the given param (eg, maxWidth) to the requested value
	 * 
	 * @param String $param 
	 * @param Mixed $value 
	 * 
	 * @return Void    
	 */
	public function setParam($param, $value) {
		$this->params[$param] = $value;
		$this->populateCachePaths();
	}
	
	/**
	 * Sets the given params (eg, maxWidth) to the requested values
	 * 
	 * @param Array $params key-value pairs of params, values
	 * 
	 * @return Void    
	 */
	public function setParams(Array $params) {
		foreach ($params as $p => $v) {
			$this->setParam($p, $v);	
		}
		$this->populateCachePaths();
	}
	
	/**
	 * Returns the value of the given param
	 * 
	 * @param String $param 
	 * 
	 * @return Mixed    
	 */
	public function getParam($param) {
		if (isset($this->params[$param])) {
			return $this->params[$param];
		}
		return NULL;
	}
	
	

	/**
	 * Log a message for debugging purposes
	 * @param	String	$msg	
	 * @return	Void		
	 */
	protected function log($msg) {
		$this->errors[] = $msg;
	}

	
	public function populateSourcePaths() {
		// interpret whether $sourcepath is a relative, or absolute, path:
		if (strpos($this->getInputSourcePath(), "/") === 0) {
			// found a "slash" at the beginning of the string, so it's almost definitely absolute.
			// that's good enough.
			$this->setSourcePath($this->getInputSourcePath());
		} else {
			// okay, calling code input a relative path. this is extremely common.
			// make use of the sourceProtected value to prepend a leading 'public' or 'protected'
			// component
			if ($this->sourceProtected) {
				$relpath = "protected/" .  $this->getInputSourcePath();
			} else {
				$relpath = "public/" . $this->getInputSourcePath();
			}
						
			// Okay, now does this constructed path actually exist as a file under APP_PATH?
			if (file_exists(APP_PATH . "/" . $relpath)) {
				$this->setSourcePath(realpath(APP_PATH . "/" . $relpath));
			} else {
				$this->log("__construct: ambiguous path specified ({$relpath})");
				return false;
			}
		}
	}
	
	/**
	 * Resolve the various cache-related paths for use in this class.
	 * NOTE: this method relies on resolveCacheSubdirFromParams(), which in turn expects all setParam()
	 *   calls to be finished. This method should only be called after all params (eg maxWidth...) have
	 *   been assigned via setParam().  Ideally, just call this in url() and execute().
	 *
	 *
	 * Examples of what gets set here:
	 * cachedir: /home/user/production/cms2/image_cache/sitename/blog_photo1/abcdefg.jpg/800_600...etc
	 * filename: abcdefg.png  (where PNG is the requested outputFormat)
	 * cachepath: /home/user/production/cms2/image_cache/sitename/blog_photo1/abcdefg.jpg/800_600...etc.../abcdefg.png
	 * 
	 * @return Void
	 */
	public function populateCachePaths() {
		
		// by default, use the public cache dir base located in the engine directory
		// because legacy installations already have this directory but don't have
		// image_cache directories under the app directory tree
		if ($this->getCacheDirBase() == false) {
			$this->setCacheDirBase(ENGINE_PUBLIC_PATH . "/image_cache/" . APP_NAME);
		}
		
		// get the subdirectory based on image paramters
		$subdir = $this->resolveCacheSubdirFromParams();
		
		// get the file extension
		$pathInfo = pathinfo($this->getSourcePath());
				
		// derive cache paths and filename. use raw quality value, not translated, so the paths are consistent
		$this->cachedir = $this->getCacheDirBase() . DIRECTORY_SEPARATOR . static::relpath($this->getSourcePath()) . DIRECTORY_SEPARATOR . $subdir ;
		$this->filename = filemtime($this->getSourcePath()) . '.' . $this->getOutputFormat();
		
		$this->cachepath = $this->cachedir . DIRECTORY_SEPARATOR . $this->filename;
		
	}
	
	/**
	 * Returns the ImageMagick object
	 * @return	Imagick
	 */
	public function getImageResource() {
		return $this->imageResource;
	}
	
	/**
	 * Given an absolute source path, return the path relative to the application
	 * directory
	 * @param	String	$sourcepath
	 * @return	String	The relative path
	 */
	protected static function relpath($sourcepath) {
		return ltrim(str_replace(APP_PATH, "", $sourcepath), "/");
	}
	

	/**
	 * Return the current log entries
	 * @return	Array of Strings
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Return the previously-set filesystem path to the source asset 
	 * @return	String		
	 */
	public function getSourcePath() {
		return $this->sourcepath;
	}
	
	/**
	 * Return the original input source path (the 'image' param)
	 * 
	 * @return String
	 */
	public function getInputSourcePath() {
		return $this->inputSourcePath;
	}

	/**
	 * Sets the absolute filesystem path to the source asset
	 * @param	String	$sourcepath	
	 * @return	Void				
	 */
	public function setSourcePath($sourcepath) {
		$this->sourcepath = $sourcepath;
	}

	
	/**
	 * Returns the absolute filesystem path to the cached asset (or where it should be)
	 * @return	String
	 */
	public function getCachePath() {
		return $this->cachepath;
	}
	
	/**
	 * Returns true/false depending on whether the file, as currently configured,
	 * exists in the cache.
	 * 
	 * @return Boolean
	 */
	public function fileInCache() {
		if (file_exists($this->getCachePath())) {
			if (filesize($this->getCachePath()) > 0) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns the filesystem path to the cached asset, relative to the cache directory
	 * @return	String		
	 */
	public function getRelativeCachePath() {
		return str_replace($this->getCacheDirBase(), "", $this->getCachePath());
	}

	/**
	 * Sets the filesystem path where the cache lives
	 * @param	String	$path
	 * @return	Void
	 */
	public function setCacheDirBase($path) {
		$this->cachedir_base = $path;
	}
	
	/**
	 * Returns the filesystem path where the cache lives
	 * @return	String
	 */
	public function getCacheDirBase() {
		return $this->cachedir_base;
	}
	
	/**
	 * Returns the directory that holds (or will hold) this image file
	 * 
	 * @return String	Filesystem path
	 */
	public function getCacheDir() {
		return $this->cachedir;
	}
	
	/**
	 * Instructs the class whether to use the protected cache directory tree for this image
	 * 
	 * @param Boolean $bool
	 * 
	 * @return Void
	 */
	public function setCacheProtected($bool) {
		$this->cacheProtected = $bool;
		if ($bool == true) {
			$this->setCacheDirBase(ENGINE_PROTECTED_PATH . "/image_cache/" . APP_NAME);	
		} elseif ($bool == false) {
			$this->setCacheDirBase(ENGINE_PUBLIC_PATH . "/image_cache/" . APP_NAME);
		}
	}
	
	/**
	 * Instructs the class whether to look for this image in the protected directory tree
	 * 
	 * @param Boolean $bool
	 * 
	 * @return Void
	 */
	public function setSourceProtected($bool) {
		$this->sourceProtected = $bool;
	}
	
	
	/**
	 * Returns the URL, suitable for use in an img element SRC tag.
	 * - If using an unprotected cache and the cached file already exists, return a direct URL to the cached file.
	 * - If a protected cache is in use, return an image.php URL.
	 * - If no file exists yet in the cache, return an image.php URL (so that the first visitor to encounter it
	 * will trigger its generation -- future visitors will get a direct URL to the cached file)
	 *
	 * @return String    URL
	 */
	public function url() {
		$this->populateCachePaths();
		
		if ($this->cacheProtected == TRUE || ! $this->fileInCache()) {
			return $this->scriptURL();
		} else {
			
			return $this->cachedURL();
			
		}
		
	}
	
	public function scriptURL() {
		$params = $this->params;
		$params['canvasBackgroundColor'] = str_replace("#", "", $this->getParam('canvasBackgroundColor'));
		$params['sourceProtected'] = $this->sourceProtected;
		$params['cacheProtected'] = $this->cacheProtected;
		
		if ($this->getParam('canvasWidth') == false) {
			unset ($params['canvasWidth']);
		}
		if ($this->getParam('canvasHeight') == false) {
			unset ($params['canvasHeight']);
		}
		if ($this->getParam('maxArea') == false) {
			unset ($params['maxArea']);
		}
		if ($this->getParam('maxWidth') == false) {
			unset ($params['maxWidth']);
		}
		if ($this->getParam('maxHeight') == false) {
			unset ($params['maxHeight']);
		}
		
		$url = SITE_URL . "/?image=" . $this->getInputSourcePath() . "&" . http_build_query($params);
		
		return htmlentities($url);
	}
	
	public function cachedURL() {
		$this->populateCachePaths();
		$url = ENGINE_URL . '/image_cache/' . APP_NAME	.  $this->getRelativeCachePath();
		return $url;
	}
	

	public function writeContentTypeHeader() {
		if ($this->getOutputFormat() == 'jpg' || $this->getOutputFormat() == 'jpeg') {
			header("Content-Type: image/jpeg");
		} elseif ($this->getOutputFormat() == 'png') {
			header("Content-Type: image/png");
		} elseif ($this->getOutputFormat() == 'gif') {
			header("Content-Type: image/gif");
		}
	}
	
	/**
	 * Writes file contents to the output buffer.
	 * NOTE: does not send headers.
	 * 
	 * @return Void
	 */
	public function readImage() {
		if ($this->imageResource == false) {
			readfile($this->getCachePath());
		} else {
			echo $this->imageResource;
		}
	}

	/**
	 * Returns the filesize of the image, in bytes
	 * 
	 * @return Integer
	 */
	public function resourceSize() {
		if ($this->imageResource == false) {
			return filesize($this->cachepath);
		} else {
			return strlen($this->imageResource);		
		}
	}
	
	/**
	 * Set the output format
	 * @param	String	$format	<png|jpg|gif>
	 * @return	Void			
	 */
	public function setOutputFormat($format) {
		if ($format == "png" || $format == "jpg" || $format == 'jpeg' || $format == "gif") {
			if ($format == "jpg" || $format == 'jpeg' ) {
				$format = 'jpg';
			}
			$this->setParam('outputFormat', $format);
		} else {
			$this->log("__setOutputFormat: invalid format provided.");
		}
	}

	public function getOutputFormat() {
		
		$format = $this->getParam('outputFormat');
		return $format;
	}
	
	/**
	 * Return true if calling code has requested a background canvas
	 * 
	 * @return Boolean
	 */
	public function hasCanvas() {
		return ($this->getParam('canvasHeight') != '' && $this->getParam('canvasWidth') != false);
	}
	
	/**
	 * Sets the canvas background color. Only applies when canvasWidth and canvasHeight are set.
	 * 
	 * @param String $str either a hex code or a color name, or "none" for transparent (gif, png only)
	 * 
	 * @return Void   
	 */
	public function setCanvasBackgroundColor($str) {
		$this->setParam('canvasBackgroundColor', $str);
	}
	

	/**
	 * Sets the quality parameter (according to the JPG 0-100 worst-best scale)
	 * @param	Integer	$quality
	 * @return	Void				
	 */ 
	public function setQuality($quality) {
		// default to no-compression
		if ($quality === '') {
			$quality = 100;
		} elseif ($quality > 100) {
			$quality = 100;
		} elseif ($quality < 0) {
			$quality = 100;
		}
		$this->setParam('quality', $quality);
	}

	/**
	 * Returns the quality parameter setting
	 * @return	Integer		0 to 100
	 */	
	public function resolveQuality() {
		if ($this->getOutputFormat() == "png") {
			return round(abs((($this->getParam('quality') - 100) / 11.111111)));
		} else {
			return $this->getParam('quality');
		}
	}
	
	/**
	 * Performs the image generation and cache read/write operation
	 * @return	Boolean
	 */
	public function execute() {
		
		$this->populateCachePaths();
		
		if (! file_exists($this->getSourcePath()) ) {
			$this->log("execute(): sourcepath does not exist on disk ({$this->sourcepath})");
			return false;
		}
		
		if ($this->sourceProtected && ! $this->protectedImageIsAuthorized($this->getInputSourcePath())) {
			echo "PERMISSION DENIED.";
			exit;
		}

		// only write to the cache if we have to
		if ($this->forceCache == true || ! $this->fileInCache()) {
			if ($this->forceCache == true) {
				$this->log("forceCache is true; going to run makeImage() for {$this->sourcepath}");
			} elseif (! $this->fileInCache()) {
				$this->log("cached file not found; going to run makeImage() for {$this->sourcepath}");
			}

			// manipulate the image			
			if ($this->makeImage()) {
				$this->log("running makeImage()");
				// Make sure the cache directory exists and is 777
				if (! file_exists($this->getCacheDir())) {
					$_old = umask(0);
					@mkdir($this->cachedir, 0777, true);
					umask($_old);
				}
				
				// Write to the image cache
				$retval = $this->writeImage($this->getCachePath());

				// Make sure it worked
				if ($retval != false) {
					chmod($this->getCachePath(), 0777);
					return true;
				} else {
					$this->log("execute(): image outputfailed on write to disk.");
					return false;
				}
			} else {
				$this->log("execute(): makeImage() ran, but imageResource is false.");
				return false;
			}
		} else {
			$this->log("Cache hit for {$this->sourcepath}; skipping makeImage()");
		}
		return true;
	}

	
	/**
	 * Writes the image to disk
	 * 
	 * @param String $path Destination to write to
	 * 
	 * @return Boolean
	 */
	public function writeImage($path) {
		return $this->imageResource->writeImage($path);
	}
	
	
	/**
	 * Manipulates the image as requested by calling code
	 * @return	Boolean
	 */
	public function makeImage() {
		//bail out if source doesn't really exist.
		if (! file_exists($this->getSourcePath()) || $this->getSourcePath() == '' || is_dir($this->getSourcePath())) {
			$this->log("make_image: Invalid sourcepath ({$this->sourcepath})");
			return false;
		}
		// suppress warnings
		ob_start();

		// initialize a new image resource based on the original image
		$this->imageResource = new Imagick($this->getSourcePath());
		
		// obtain the original width and height of the source image
		$width = $this->imageResource->getImageWidth();
		$height = $this->imageResource->getImageHeight();

		// Read in requested settings
		$maxWidth = (int) $this->getParam('maxWidth');
		$maxHeight = (int) $this->getParam('maxHeight');
		$maxArea = (int) $this->getParam('maxArea');
				
		// Normalize those settings
		if ($maxWidth == 0) {
			$maxWidth = NULL;
		}
		if ($maxHeight == 0) {
			$maxHeight = NULL;
		}

		if ($maxArea == 0) {
			$maxArea = NULL;
		}

		// If we specify $maxWidth and/or $maxHeight, they are used to scale the image.
		// However if we've also specified $maxArea, we must check to make sure that $maxWidth
		// and $maxHeight don't multiply to exceed $maxArea.
		// If they do, use $maxArea instead.
		if (! is_null($maxWidth) || ! is_null($maxHeight) ) {
			if (is_null($maxWidth)) {
				$maxWidth = $width;
			}
			if (is_null($maxHeight)) {
				$maxHeight = $height;
			}

			if ($width < $maxWidth) {
				$maxWidth = $width;
			}
			if ($height < $maxHeight) {
				$maxHeight = $height;
			}


			// Determine the ratio of the desired size to the actual size
			$x_ratio = $maxWidth / $width;
			$y_ratio = $maxHeight / $height;

			// Use the ratio to determine the actual requested size
			if ($this->getParam('crop') == true && $maxWidth != false && $maxHeight != false) {
				$performCrop = true;
				if ($x_ratio < $y_ratio) {
					$newWidth = ceil($y_ratio * $width);
					$newHeight = ceil($y_ratio * $height);
					$croppedWidth = $maxWidth;
					$croppedHeight = $newHeight;
					$cropaction = "width";
					$cropvalue = $newWidth - $croppedWidth;
				}
				else {
					$newWidth = ceil($x_ratio * $width);
					$newHeight = ceil($x_ratio * $height);
					$croppedWidth = $newWidth;
					$croppedHeight = $maxHeight;
					$cropaction = "height";
					$cropvalue = $newHeight - $croppedHeight;
				}

			}
			
			elseif ( ($width <= $maxWidth) && ($height <= $maxHeight) ) {
			  $newWidth = $width;
			  $newHeight = $height;
			}
			
			elseif (($x_ratio * $height) < $maxHeight) {
			  $newHeight = ceil($x_ratio * $height);
			  $newWidth = $maxWidth;
			  
			  
			}
			
			else {
			  $newWidth = ceil($y_ratio * $width);
			  $newHeight = $maxHeight;
			}

			/// Override with $maxArea if necessary
			if (! is_null($maxArea)) {
				if ($newWidth * $newHeight > $maxArea) {
					$newHeight = pow($maxArea / ($width * $height) , 1/2) * $height;
					$newWidth = pow($maxArea / ($width * $height) , 1/2) * $width;
				}
			}
		} else {
			// No $maxWidth or $maxHeight ... try $maxArea
			if (! is_null($maxArea) ) {
				$newHeight = pow($maxArea / ($width * $height) , 1/2) * $height;
				$newWidth = pow($maxArea / ($width * $height) , 1/2) * $width;
			} else {
				/* No $maxArea either... oh well, send it out at the original image dimensions! */
				$newHeight = $height;
				$newWidth = $width;
			}

		}
	
		// now that we've figured out width and height, resize the image
		$this->imageResource->resizeImage($newWidth,$newHeight,Imagick::FILTER_LANCZOS,1);
		
		// crop if necessary, using calculated dimensions
		if ($performCrop == true) {
			if ($cropaction == "width") {
				$this->crop($croppedWidth, $croppedHeight, $cropvalue / 2, 0);
			} elseif ($cropaction == "height") {
				$this->crop($croppedWidth, $croppedHeight, 0, $cropvalue / 2);				
			}
		}

		// if a background canvas has been requested, create that now and
		// superimpose the image on top of it
		if ($this->hasCanvas()) {
			$dst_x = floor(($this->getParam('canvasWidth') - $this->imageResource->getImageWidth()) / 2);
			$dst_y = floor(($this->getParam('canvasHeight') - $this->imageResource->getImageHeight()) / 2);
			
			if (static::isHexColor($this->getParam('canvasBackgroundColor'))) {
				$canvasBackgroundColor = "#" . str_replace("#", "", $this->getParam('canvasBackgroundColor'));
			} else {
				$canvasBackgroundColor = $this->getParam('canvasBackgroundColor');
			}
			$canvas = new Imagick;
			$canvas->newImage($this->getParam('canvasWidth'), $this->getParam('canvasHeight'), $canvasBackgroundColor);
			$canvas->compositeImage($this->imageResource, Imagick::COMPOSITE_COPY, $dst_x, $dst_y);
			
			$this->imageResource->destroy();
			unset($this->imageResource);
			$this->imageResource = $canvas;

		}
		
		// set the image format to whichever was requested by calling code
		$this->imageResource->setImageFormat($this->getOutputFormat());
		
		// handle compression, depending on format
		if ($this->getOutputFormat() == 'jpg') {
			$this->imageResource->setCompression(Imagick::COMPRESSION_JPEG);
			$this->imageResource->setCompressionQuality($this->resolveQuality());
		}
		elseif ($this->getOutputFormat() == 'png') {
			
			$this->imageResource->setCompression(Imagick::COMPRESSION_ZIP);
			$this->imageResource->setImageDepth(8);
			$this->imageResource->stripImage();
			$this->imageResource->setCompressionQuality($this->resolveQuality());			
		}
		
		
		ob_end_clean();
		
		return ($this->imageResource !== false);

	}
	
	/**
	 * Crop the image 
	 * @param	Integer	$cropped_width	
	 * @param	Integer	$cropped_height	
	 * @param	Integer	$x_topleft		
	 * @param	Integer	$y_topleft		
	 * @return	Void					
	 */
	public function crop($cropped_width, $cropped_height, $x_topleft, $y_topleft) {
		$this->imageResource->cropImage($cropped_width, $cropped_height, $x_topleft, $y_topleft);
	}
	
	/**
	 * Crop an image located on disk and write it to disk
	 * @param	String	$fileIn			
	 * @param	String	$fileOut		
	 * @param	Integer	$cropped_width	
	 * @param	Integer	$cropped_height	
	 * @param	Integer	$x_topleft		
	 * @param	Integer	$y_topleft		
	 * @return	Void					
	 */
	public static function cropImageOnDisk($fileIn, $fileOut, $cropped_width, $cropped_height, $x_topleft, $y_topleft) {
		$cls = get_called_class();
		$image = new $cls($fileIn);
		$image->crop($cropped_width, $cropped_height, $x_topleft, $y_topleft);
		$image->getImageResource()->writeImage($fileOut);
	}
	
	/**
	 * Return true if the supplied value is a hex color code, false otherwise
	 * @author tom@hgmail.com
	   @link http://us2.php.net/manual/en/function.ctype-xdigit.php#60707
	 * 
	 * @param string $colorCode  Eg. ffffff or fff, but not 'red'
	 * 
	 * @return Boolean
	 */
	public static function isHexColor($colorCode) {
		// If user accidentally passed along the # sign, strip it off
		$colorCode = ltrim($colorCode, '#');
		
		if (ctype_xdigit($colorCode) && (strlen($colorCode) == 6 || strlen($colorCode) == 3))
			return true;		
		return false;
	}

	/**
	 * Legacy/deprecated methods
	 */
	public function setMaxWidth($param) {
		$this->setParam("maxWidth", $param);
	}
	
	public function setMaxHeight($param) {
		$this->setParam("maxHeight", $param);
	}
	
	public function setCrop($param) {
		$this->setParam("crop", $param);
	}
	
	public function make_image() {
		$this->makeImage();
	}
	
	public function do_cache() {
		$this->execute();
	}
	
}