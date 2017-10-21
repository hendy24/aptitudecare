<?php
date_default_timezone_set('America/Boise');
if (! defined('DEVELOPMENT') ) define('DEVELOPMENT', false);
if (! defined('STAGING' )) define('STAGING', false);
if (! defined('SLICING')) define('SLICING', false);
if (! defined('GOOGLE_ANALYTICS')) define('GOOGLE_ANALYTICS', '');

// For backward compatibility with older apps, figure out whether PRODUCTION should actually be true
if (! defined('PRODUCTION')) {
	if (DEVELOPMENT == false && STAGING == FALSE && SLICING == FALSE) {
		define('PRODUCTION', true);
	} else {
		define('PRODUCTION', false);
	}
}

if (! defined('ENGINE_PROTECTED_PATH') ) {
	throw new Exception("ENGINE_PROTECTED_PATH not defined. Please define this in bootstrap.php");
}
if (! defined('ENGINE_PUBLIC_PATH') ) {
	throw new Exception("ENGINE_PUBLIC_PATH not defined. Please define this in bootstrap.php");
}

//If no SECURE_URL, set it to equal SITE_URL
//if (! defined('SECURE_URL') ) {
//	define('SECURE_URL', SITE_URL);
//}

// some old sites had SITE_URL and ENGINE_URL as a define in env.php
if (defined('SITE_URL')) {
	$SITE_URL = SITE_URL;
}
if (defined('ENGINE_URL')) {
	$ENGINE_URL = ENGINE_URL;
}


if (! isset($SECURE_URL) || (isset($SECURE_URL) && $SECURE_URL == '')) {
	$SECURE_URL = $SITE_URL;
}
if (! isset($SECURE_ENGINE_URL) || (isset($SECURE_ENGINE_URL) && $SECURE_ENGINE_URL == '')) {
	$SECURE_ENGINE_URL = $ENGINE_URL;
}
if (! isset($SECURE_CDN_URL) || (isset($SECURE_CDN_URL) && $SECURE_CDN_URL == '')) {
	$SECURE_CDN_URL = $CDN_URL;
}
if (! isset($CDN_ENGINE_URL) || (isset($CDN_ENGINE_URL) && $CDN_ENGINE_URL == '')) {
	$CDN_ENGINE_URL = $ENGINE_URL;
}
if (! isset($SECURE_CDN_ENGINE_URL) || (isset($SECURE_CDN_ENGINE_URL) && $SECURE_CDN_ENGINE_URL == '')) {
	$SECURE_CDN_ENGINE_URL = $CDN_ENGINE_URL;
}

if (! isset($ENGINE_URL) || (isset($ENGINE_URL) && $ENGINE_URL == '')) {
	$ENGINE_URL = $SITE_URL . "/cms2-public";
}

if (! isset($CDN_URL) || (isset($CDN_URL) && $CDN_URL == '')) {
	$CDN_URL = $SITE_URL;
}

$NONSECURE_URL = $SITE_URL;
$NONSECURE_ENGINE_URL = $ENGINE_URL;

// Most applications are small and caching can be hazordous. Set off by default.
if (! defined('ORM_CACHE_WRITE')) {
	define('ORM_CACHE_WRITE', false);
}
if (! defined('ORM_CACHE_UTILIZE')) {
	define('ORM_CACHE_UTILIZE', false);
}

//If we're on port 443:
if ($_SERVER["SERVER_PORT"] == 443) {
	//Let the app know we're secure
	define('SECURE', true);

	//Replace SITE_URL with SECURE_URL
	//define('SITE_URL', SECURE_URL);
	$SITE_URL = $SECURE_URL;
	$ENGINE_URL = $SECURE_ENGINE_URL;
	$CDN_URL = $SECURE_CDN_URL;
	$CDN_ENGINE_URL = $SECURE_CDN_ENGINE_URL;

} else {
	//Let the app know we're not secure
	define('SECURE', false);

}

if (! isset($COOKIE_DOMAIN) || (isset($COOKIE_DOMAIN) && $COOKIE_DOMAIN == '')) {
	$COOKIE_DOMAIN = "." . str_replace("http://", "", str_replace("https://", "", str_replace(":" . $_SERVER["SERVER_PORT"], "", $SITE_URL)));
}
define('SITE_URL', $SITE_URL);
define('ENGINE_URL', $ENGINE_URL);
define('SECURE_URL', $SECURE_URL);
define('SECURE_ENGINE_URL', $SECURE_ENGINE_URL);
define('NONSECURE_URL', $NONSECURE_URL);
define('NONSECURE_ENGINE_URL', $NONSECURE_ENGINE_URL);
define('CDN_URL', $CDN_URL);
define('CDN_ENGINE_URL', $CDN_ENGINE_URL);
define('COOKIE_DOMAIN', $COOKIE_DOMAIN);

if (! defined('MINIFY_JS') ) {
	// by default, do not minify.
	define('MINIFY_JS', false);
}

if (!defined('JQUERY_URL')) {
	define('JQUERY_URL', CDN_ENGINE_URL . '/js/jquery/jquery-1.6.1.min.js');
}

// Register engine-defined autoloads, in order of precedence:
$autoload_batches = scandir(ENGINE_PROTECTED_PATH . "/autoload");
foreach ($autoload_batches as $autoload_batch) {
	if ($autoload_batch == '.' || $autoload_batch == '..') continue;
	if (is_dir(ENGINE_PROTECTED_PATH . "/autoload/{$autoload_batch}")) {
		$autoloads = scandir(ENGINE_PROTECTED_PATH . "/autoload/{$autoload_batch}");
		foreach ($autoloads as $autoload) {
			if ($autoload == '.' || $autoload == '..') continue;
			require_once ENGINE_PROTECTED_PATH . "/autoload/{$autoload_batch}/{$autoload}";	
		}
	}
}
// Register app-defined autoloads, in order of precedence:
if (file_exists(APP_PROTECTED_PATH . "/autoload")) {
	$autoload_batches = scandir(APP_PROTECTED_PATH . "/autoload");
	foreach ($autoload_batches as $autoload_batch) {
		if ($autoload_batch == '.' || $autoload_batch == '..') continue;
		if (is_dir(APP_PROTECTED_PATH . "/autoload/{$autoload_batch}")) {
			$autoloads = scandir(APP_PROTECTED_PATH . "/autoload/{$autoload_batch}");
			foreach ($autoloads as $autoload) {
				if ($autoload == '.' || $autoload == '..') continue;
				require_once APP_PROTECTED_PATH . "/autoload/{$autoload_batch}/{$autoload}";	
			}
		}
	}
}

if (defined('SITE_LOG_TOTAL_RUNTIME') && SITE_LOG_TOTAL_RUNTIME == true) {
	MainControllerBase::startTimer();
}

require_once ENGINE_PROTECTED_PATH . "/lib/helpers.generic.php";

if (! is_CLI() ) {
	ini_set('session.cookie_domain', COOKIE_DOMAIN);
	session_start();
}
// init smarty

$smarty = new Smarty();
$smarty->template_dir = APP_PROTECTED_PATH . '/tpl';
$smarty->compile_dir = APP_PROTECTED_PATH . '/cpl';
$smarty->cache_dir = APP_PROTECTED_PATH . '/smarty_cache';
$smarty->config_dir = APP_PROTECTED_PATH . '/smarty_configs';
$smarty->escape_html = true;
$smarty->assign("cms_template_dir", ENGINE_PROTECTED_PATH . "/tpl");
$smarty->assign("email_template_dir", APP_PROTECTED_PATH . "/tpl_email");
$smarty->assign("template_dir", APP_PROTECTED_PATH . '/tpl');		// $template_dir stopped working from Smarty-3.0.6 => Smarty 3.0.7
// Smarty caching
if (DEVELOPMENT == true && STAGING == false && SLICING == false) {
	// pure development environment -- force compile; caching off
	$smarty->force_compile = true;
	$smarty->caching = Smarty::CACHING_OFF;
} else {
// staging -- don't force compile; that seriously degrades performance. still don't cache though.
	$smarty->force_compile = false;                 
	$smarty->caching = Smarty::CACHING_OFF;
}


if (! file_exists($smarty->compile_dir)) {
	throw new Exception("The directory {$smarty->compile_dir} does not exist. Please create it and set it world-writable.");
}

require_once ENGINE_PROTECTED_PATH . "/exception/CommonExceptions.php";
require_once ENGINE_PROTECTED_PATH . "/lib/helpers.smarty.functions.php";
require_once ENGINE_PROTECTED_PATH . "/lib/helpers.smarty.prefilter.php";
require_once ENGINE_PROTECTED_PATH . "/lib/helpers.smarty.outputfilter.php";

if (file_exists(APP_PROTECTED_PATH . "/lib/helpers.php")) {
	require_once APP_PROTECTED_PATH . "/lib/helpers.php";
}
if (file_exists(APP_PROTECTED_PATH . "/lib/Exceptions.php")) {
	require_once APP_PROTECTED_PATH . "/lib/Exceptions.php";
}


define('CURRENT_URL', currentURL());

// disable email queuing in development
if (DEVELOPMENT == TRUE && STAGING == FALSE && SLICING == FALSE) {
	Email::$disableQueue = true;
}

// feedback messages
$feedback = Feedback::getInstance();

// GET and POST vars
$input = Input::getInstance();

// session variable interface
$carry = Carry::getInstance();

// global accessors
if (! function_exists("dbCMS")) {
	function dbCMS() {
		global $dbCMS;
		return $dbCMS;
	}
}
if (! function_exists("smarty")) {
	function smarty() {
		global $smarty;
		return $smarty;
	}
}

if (! function_exists("db")) {
	function db() {
		global $db;
		return $db;
	}
}
if (! function_exists("input")) {
	function input() {
		global $input;
		return $input;
	}
}
if (! function_exists("carry")) {
	function carry() {
		global $carry;
		return $carry;
	}
}
if (! function_exists("feedback")) {
	function feedback() {
		global $feedback;
		return $feedback;
	}
}

// assign...
smarty()->assign(array(
	"carry" => carry(),
	"SITE_URL" => SITE_URL,
	"SECURE_URL" => SECURE_URL,
	"NONSECURE_URL" => NONSECURE_URL,
	"ENGINE_URL" => ENGINE_URL,
	"NONSECURE_ENGINE_URL" => NONSECURE_ENGINE_URL,
	"SECURE_ENGINE_URL" => SECURE_ENGINE_URL,
	"CDN_URL" => CDN_URL,
	"CDN_ENGINE_URL" => CDN_ENGINE_URL,
	"JQUERY_URL" => JQUERY_URL,
	"CURRENT_URL" => CURRENT_URL,
	"DEVELOPMENT" => DEVELOPMENT,
	"GOOGLE_ANALYTICS" => GOOGLE_ANALYTICS,
	"SECURE" => SECURE,
	"_history" => input()->getHistory(),
	"APP_PATH" => APP_PATH,
	"ENGINE_PROTECTED_PATH" => ENGINE_PROTECTED_PATH,
	"ENGINE_PUBLIC_PATH" => ENGINE_PUBLIC_PATH,
	"_input" => input()
));
smarty()->assign("feedback", feedback());