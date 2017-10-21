<?php
spl_autoload_register(function($cls) {
	$path = APP_PROTECTED_PATH . "/lib/class.{$cls}.php";
	if (file_exists($path)) {
		require_once $path;
	}
});
spl_autoload_register(function($cls) {
	$path = ENGINE_PROTECTED_PATH . "/lib/class.{$cls}.php";
	if (file_exists($path)) {
		require_once $path;
	}
});