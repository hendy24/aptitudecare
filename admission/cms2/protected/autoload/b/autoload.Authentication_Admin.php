<?php
spl_autoload_register(function($cls) {
	if ($cls == "Authentication_Admin") {
		eval("class Authentication_Admin extends Authentication_Admin_Base { }");
	}
});
	