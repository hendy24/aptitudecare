<?php
spl_autoload_register(function($class) {
	if ($class == "PHPMailer") {
		require ENGINE_PROTECTED_PATH . "/lib/contrib/PHPMailer_v5.1/class.phpmailer.php";
		require ENGINE_PROTECTED_PATH . "/lib/contrib/PHPMailer_v5.1/class.smtp.php";
	}
});