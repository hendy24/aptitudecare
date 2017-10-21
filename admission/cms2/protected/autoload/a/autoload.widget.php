<?php
spl_autoload_register(function($cls) {
	if (preg_match("/^Widget/", $cls)) {
		$path = APP_PROTECTED_PATH . "/lib/widgets/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});
spl_autoload_register(function($cls) {
	if (preg_match("/^Widget/", $cls)) {
		$path = ENGINE_PROTECTED_PATH . "/lib/widgets/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});