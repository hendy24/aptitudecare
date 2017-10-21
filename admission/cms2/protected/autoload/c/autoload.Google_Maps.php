<?php
spl_autoload_register(function($cls) {
	if ($cls == "GoogleMapAPI") {
		$path = ENGINE_PROTECTED_PATH . "/lib/contrib/GoogleMapAPI-2.5/GoogleMapAPI.class.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});