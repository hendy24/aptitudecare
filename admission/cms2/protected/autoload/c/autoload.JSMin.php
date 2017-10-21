<?php
spl_autoload_register(function($cls) {
	if ($cls == "JSMin") {
		$path = ENGINE_PROTECTED_PATH . "/lib/contrib/rgrove-jsmin-php-8689392/jsmin.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});