<?php
spl_autoload_register(function($cls) {
	if ($cls == "MainController" || preg_match("/^PageController/", $cls)) {
		$path = APP_PROTECTED_PATH . "/controller/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});
spl_autoload_register(function($cls) {
	if ($cls == "MainControllerBase" || preg_match("/^PageController/", $cls)) {
		$path = ENGINE_PROTECTED_PATH . "/controller/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});