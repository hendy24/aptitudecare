<?php 

/**
 *	Index
 *
 *	This is the main page for handling all requests.  The apache config file should be set for the URL to go straight to this page.
 *
 * 	PHP 5
 *
 *	This framework was developed by Aptitude specifically for the AptitudeCare suite of health care software solutions.
 *	
 *	@copyright  	Copyright 2014, Aptitude IT, LLC
 * 	@version  		AptitudeFramework version 1.0
 */
 
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}

/**
 *	If the apache config file is set correctly the root directory will not yet be defined.  Define it here.
 */

	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(dirname(__FILE__)))));
	}
	
	if (!defined('APP_DIR')) {
		define('APP_DIR', dirname(dirname(__FILE__)));
	}
	
/**
 *
 *	Define the path to the protected and public directories
 */

 	define('FRAMEWORK_DIR', APP_DIR . DS . 'framework');
 	define('FRAMEWORK_PROTECTED_DIR', FRAMEWORK_DIR . DS . 'protected');
 	define('FRAMEWORK_PUBLIC_DIR', FRAMEWORK_DIR . DS . 'public');
	define('APP_PUBLIC_DIR', APP_DIR . DS . 'public');
	define('APP_PROTECTED_DIR', APP_DIR . DS . 'protected');
	define('MODULES_DIR', APP_DIR . DS . 'modules');
	define('VENDORS_DIR', FRAMEWORK_PROTECTED_DIR . DS . 'Vendors');
	define('S3_BUCKET', 'http://advanced-health-care.s3.amazonaws.com');


	// Use https, otherwise the site stylesheets and images will not load properly
	define('SITE_URL', 'https://' . $_SERVER['SERVER_NAME']);
	define('APP_NAME', 'AptitudeCare');
	
/** 
 *
 * Include the bootstrap file in the protected directory and we're off!
 */

	require(FRAMEWORK_DIR . DS . 'bootstrap.php');



