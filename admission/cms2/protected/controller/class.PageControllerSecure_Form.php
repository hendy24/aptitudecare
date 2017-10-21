<?php

class PageControllerSecure_Form extends PageController {

	public function index() {
		if (! isset($_SESSION[APP_NAME]["_secure_form"]["salt"])) {
			$_SESSION[APP_NAME]["_secure_form"]["salt"] = random_string(10);
		}

		$ct = mktime();
		setcookie('_secure_form_token',md5($_SESSION[APP_NAME]["_secure_form"]["salt"].$ct), 0, '/', COOKIE_DOMAIN);

		# 'Expires' in the past
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		# Always modified
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

		# HTTP/1.1
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);

		# HTTP/1.0
		header("Pragma: no-cache");
		echo $ct;

		exit;
	}
}