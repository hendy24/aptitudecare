<?php
spl_autoload_register(function($cls) {
	if ($cls == "MainController") {
		eval("class MainController extends MainControllerBase { }");
	}
});