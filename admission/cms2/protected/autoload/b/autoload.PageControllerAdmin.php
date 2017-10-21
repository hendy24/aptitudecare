<?php
spl_autoload_register(function($cls) {
	if ($cls == "PageControllerAdmin") {
		eval("class PageControllerAdmin extends PageControllerAdminBase { }");
	}
});