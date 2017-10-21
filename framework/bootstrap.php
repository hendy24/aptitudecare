<?php 

/*
 *	Set site directories
 */
 	
	define('CSS', SITE_URL . DS . 'css');
	define('IMAGES', SITE_URL . DS . 'img');
	define('JS', SITE_URL . DS . 'js');
	define('FILES', SITE_URL . DS . 'files');
	define('FONTS', SITE_URL . DS . 'fonts');
	define('TEMPLATES', SITE_URL . DS . 'templates');
	define('VIEWS', APP_PROTECTED_DIR . DS . 'Views');

	// Framework directories
	define('FRAMEWORK_URL', SITE_URL . DS . 'aptitude-public');
	define('FRAMEWORK_CSS', FRAMEWORK_URL . DS . 'css');
	define('FRAMEWORK_IMAGES', FRAMEWORK_URL . DS . 'img');
	define('FRAMEWORK_JS', FRAMEWORK_URL . DS . 'js');


/*
 * Error Reporting
 *
 */
 
 	//set_error_handler('_exeption_handler');

 	if (file_exists(APP_DIR . DS . '.development')) {
	 	ini_set('html_errors', 'on');
	 	ini_set('display_errors', 'on');
 	} else {
	 	ini_set('html_errors', 'off');
	 	ini_set('display_errors', 'off');
 	}
	
	
/*
 * -------------------------------------------
 * INCLUDE ALL REQUIRED FILES
 * -------------------------------------------
 *
 */	
 	
 	//require_once(FRAMEWORK_PROTECTED_DIR . DS . 'Vendors/Smarty-3.1.19/libs/Smarty.class.php');
 	require(FRAMEWORK_PROTECTED_DIR . DS . 'Controllers' . DS . 'MainController.php');
 	require_once(FRAMEWORK_PROTECTED_DIR . DS . 'Vendors' . DS . 'Smarty-3.1.19' . DS . 'libs' . DS . 'Smarty.class.php');
 	require_once (FRAMEWORK_PROTECTED_DIR . DS . 'Vendors' . DS . 'PHPMailer' . DS . 'PHPMailerAutoload.php');
 	require(FRAMEWORK_PROTECTED_DIR . DS . 'Libs/Singleton.php');
 	require(FRAMEWORK_PROTECTED_DIR . DS . 'Libs/Common.php');
 	require(FRAMEWORK_PROTECTED_DIR . DS . 'Libs/Authentication.php');
 	require_once(FRAMEWORK_PROTECTED_DIR . DS . 'Libs' . DS .'MySqlDb.php');
  	require_once(APP_PROTECTED_DIR . DS . 'Configs/config.php');  
  	require_once(APP_PROTECTED_DIR . DS . 'Configs/database.php');  	
  	
  	spl_autoload_register('__autoload');
 	
 	function __autoload($className) {
	 	// list of directories to scan
		$dirs = array(
			FRAMEWORK_PROTECTED_DIR . DS . 'Controllers',
			FRAMEWORK_PROTECTED_DIR . DS . 'Libs/',
			FRAMEWORK_PROTECTED_DIR . DS . 'Libs/Components/',
			FRAMEWORK_PROTECTED_DIR . DS . 'Models/',
			APP_PROTECTED_DIR . DS . 'Controllers/',
			APP_PROTECTED_DIR . DS . 'Libs/',
			APP_PROTECTED_DIR . DS . 'Libs/Components/',
			APP_PROTECTED_DIR . DS . 'Helpers/',
			APP_PROTECTED_DIR . DS . 'Models/',
		);


		// Scan the modules directory for names of existing modules
		$module_dirs = preg_grep('/^([^.])/', scandir(MODULES_DIR));
		

		// if the file exists in any of the defined directories, require it...	
		foreach ($dirs as $d) {
			if (file_exists("{$d}/{$className}.php")) {
				require_once ("{$d}/{$className}.php");
			} elseif (file_exists("{$d}/{$className}Controller.php")) {
				require_once ("{$d}/{$className}Controller.php");
			} elseif (file_exists("{$d}/{$className}Component.php")) {
				require_once ("{$d}/{$className}Component.php");
			} elseif (file_exists("{$d}/{$className}Helper.php")) {
				require_once ("{$d}/{$className}Helper.php");
			} else {

				foreach ($module_dirs as $d) {
					if (file_exists(MODULES_DIR . DS . $d . DS . 'Controllers' . DS . $className.'.php')) {
						require_once (MODULES_DIR . DS . $d . DS . 'Controllers' . DS . $className.'.php');
					} elseif (file_exists(MODULES_DIR . DS . $d . DS . 'Models' . DS . $className.'.php')) {
						require_once (MODULES_DIR . DS . $d . DS . 'Models' . DS . $className.'.php');
					}
				}


			}
		}
		
		
 	}

	
		
/*
 * -------------------------------------------
 * Instantiate Smarty
 * -------------------------------------------
 *
 */
 
	$smarty = new Smarty();
	$smarty->setTemplateDir(APP_PROTECTED_DIR . DS . 'Views')
		->setCompileDir(APP_PROTECTED_DIR . DS . 'Compile')
		->setCacheDir(APP_PROTECTED_DIR . DS . 'Cache')
		->setConfigDir(APP_PROTECTED_DIR . DS . 'ViewConfigs');
	
	$smarty->assign(array(
		'APP_NAME' => APP_NAME,
		'ROOT' => ROOT,
		'SITE_URL' => SITE_URL,
		'FRAMEWORK_URL' => FRAMEWORK_URL,
		'FRAMEWORK_CSS' => FRAMEWORK_CSS,
		'FRAMEWORK_IMAGES' => FRAMEWORK_IMAGES,
		'FRAMEWORK_JS' => FRAMEWORK_JS,
		'CSS' => CSS,
		'IMAGES' => IMAGES,
		'JS' => JS,
		'FILES' => FILES,
		'VIEWS' => VIEWS,
		'flashMessages' => '',
		'APP_PROTECTED_DIR' => APP_PROTECTED_DIR,
		'MODULES_DIR' => MODULES_DIR
	));
	
	
		
	$smarty->escape_html = true;
 
/*
 * Include any additional variables to be available globally
 * 
 */
 
 	$error_messages = array();
 	global $error_messages;
 	
 	$success_messages = array();
 	global $success_messages;
 	
 	// Instantiate classes
	
	
	if (! function_exists('db')) {
		function db() {
			global $db;
			return $db;
		}
	}

	session_start();
	$session = Session::getInstance();


	if (! function_exists ('session')) {
		function session() {
			global $session;
			return $session;
		}
	} 
	$smarty->assignByRef('session', $session);


	$auth = Authentication::getInstance();
	if (! function_exists('auth')) {
		function auth() {
			global $auth;
			return $auth;
		}
	}
	
	$smarty->assignByRef('auth', $auth);


	$input = new Input();
	if (! function_exists('input')) {
		function input() {
			global $input;
			return $input;
		}
	}
	$smarty->assignByRef('input', $input);
	
	$acl = new Acl();
	if (! function_exists('Acl')) {
		function Acl() {
			global $acl;
			return $acl;
		}
	}

	if (! function_exists('smarty')) {
		function smarty() {
			global $smarty;
			return $smarty;
		}
	}


 /*
 * INCLUDE ROUTES.PHP 
 * 
 */

  
	if (file_exists (FRAMEWORK_PROTECTED_DIR . '/Configs/routes.php')) {
		require (FRAMEWORK_PROTECTED_DIR . '/Configs/routes.php');
	} else {
		echo "Make sure that " . FRAMEWORK_PROTECTED_DIR . "/Configs/routes.php exists";
		exit;
	}


