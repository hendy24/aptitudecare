<?php
spl_autoload_register(function($cls) {
	if ($cls == "SimpleCaptcha") {
		$path = ENGINE_PROTECTED_PATH . "/lib/contrib/cool-php-captcha-0.3/captcha.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});