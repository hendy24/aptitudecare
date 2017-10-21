<?php
require_once ENGINE_PROTECTED_PATH . "/lib/class.Image_Cache.php";
require_once ENGINE_PROTECTED_PATH . "/lib/helpers.generic.php";

ini_set('max_execution_time', 60);

// max width (and support for legacy 'max_width' input var)
if (isset($_REQUEST['maxWidth'])) {
	$maxWidth = trim(strip_tags($_REQUEST['maxWidth']));
} elseif (isset($_REQUEST['max_width'])) {
	$maxWidth = trim(strip_tags($_REQUEST['max_width']));
}

if (isset($_REQUEST['maxHeight'])) {
	$maxHeight = trim(strip_tags($_REQUEST['maxHeight']));
} elseif (isset($_REQUEST['max_height'])) {
	$maxHeight = trim(strip_tags($_REQUEST['max_height']));
}

if (isset($_REQUEST['maxArea'])) {
	$maxArea = trim(strip_tags($_REQUEST['maxArea']));
} elseif (isset($_REQUEST['max_area'])) {
	$maxArea = trim(strip_tags($_REQUEST['max_area']));
}

$crop = trim(strip_tags($_REQUEST['crop']));
$image = trim(strip_tags($_REQUEST['image']));
if ($image == '' && trim(strip_tags($_REQUEST['_image'])) != '') {
	$image = trim(strip_tags($_REQUEST['_image']));
}
$getFileSize = trim(strip_tags($_REQUEST['getFileSize']));
$force = trim(strip_tags($_REQUEST['force']));		//set this to 1 to force image regeneration
$forceCache = trim(strip_tags($_REQUEST['forceCache']));
$debug = trim(strip_tags($_REQUEST['debug']));
$quality = trim(strip_tags($_REQUEST['quality']));	// 0 is worst, 100 is best
$greyscale = trim(strip_tags($_REQUEST['greyscale']));
$canvasWidth = (trim(strip_tags($_REQUEST['canvasWidth'])) != '') ? trim(strip_tags($_REQUEST['canvasWidth'])) : false;
$canvasHeight = (trim(strip_tags($_REQUEST['canvasHeight'])) != '') ? trim(strip_tags($_REQUEST['canvasHeight'])) : false;
$canvasBackgroundColor = (trim(strip_tags($_REQUEST['canvasBackgroundColor'])) != '') ? trim(strip_tags($_REQUEST['canvasBackgroundColor'])) : '#ffffff';
$outputFormat = trim(strip_tags($_REQUEST['outputFormat']));
$sourceProtected = trim(strip_tags($_REQUEST['sourceProtected']));

if ($outputFormat != 'png' && $outputFormat != 'jpg' && $outputFormat != 'gif') {
	$outputFormat = 'jpg';
}

if ($greyscale == 1) {
	$greyscale = 1;
} else {
	$greyscale = 0;
}

if (defined('SOURCE_IS_PROTECTED')) {
	$sourceIsProtected = SOURCE_IS_PROTECTED;
} else {
	$sourceIsProtected = ($sourceProtected == 1) ? true : false;
}
if (defined('CACHE_IS_PROTECTED') ) {
	$cacheIsProtected = CACHE_IS_PROTECTED;
} else {
	$cacheIsProtected = $sourceIsProtected;
}

if ($force == true) {
	$forceCache = true;
}



if ($getFileSize == 1) {
	$size = @GetImageSize($image);
	if ($size == false) {
		return false;
	}
	$width = $size[0];
	$height = $size[1];
	
	//this should be read into an eval() statement when used, preferably by making a curl() call to it.
	echo '$_width' . " = $width;";
	echo '$_height' . " = $height;";
	exit;	
} else {
	$image = new Image_Cache($image, $sourceIsProtected, $cacheIsProtected);
	$image->setParams(array(
		'maxWidth' => $maxWidth,
		'maxHeight' => $maxHeight,
		'maxArea' => $maxArea,
		'crop' => $crop,
		'forceCache' => $forceCache,
		'quality' => $quality,
		'canvasWidth' => $canvasWidth,
		'canvasHeight' => $canvasHeight,
		'canvasBackgroundColor' => $canvasBackgroundColor
	));
	$image->setOutputFormat($outputFormat);
	if ($image->execute()) {
		// Getting headers sent by the client.
		$headers = apache_request_headers();
		// Checking if the client is validating his cache and if it is current.
		if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == filemtime($image->getCachePath()))) {			
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image->getCachePath())).' GMT', true, 304);
		} else {
			// Image not cached or cache outdated, we respond '200 OK' and output the image.
			
			header_remove("Pragma");
			header_remove("Cache-Control");
			$fs = stat($image->getCachePath());
			header("Cache-Control: max-age=31536000", true);
			header("Etag: ".sprintf('"%x-%x-%s"', $fs['ino'], $fs['size'],base_convert(str_pad($fs['mtime'],16,"0"),10,16)), true);
			header("Expires: " . gmdate('D, d M Y H:i:s', strtotime("+1 year")) . ' GMT', true);
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($image->getCachePath())).' GMT', true, 200);
			header('Content-Length: '. $image->resourceSize());
			$image->writeContentTypeHeader();
			$image->readImage();
			
			
		}
	}
	
	exit;
}