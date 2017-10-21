<?php
spl_autoload_register(function($class) {
	if ($class == "Smarty") {
		require ENGINE_PROTECTED_PATH . "/lib/contrib/Smarty-3.1.13/libs/Smarty.class.php";
	}
});