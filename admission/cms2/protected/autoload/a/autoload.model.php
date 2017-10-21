<?php
spl_autoload_register(function($cls) {
	if ($cls == "Model" || preg_match("/^CMS_/", $cls)) {
		$path = APP_PROTECTED_PATH . "/model/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});
spl_autoload_register(function($cls) {
	if ($cls == "Model" || preg_match("/^CMS_/", $cls)) {
		$path = ENGINE_PROTECTED_PATH . "/model/class.{$cls}.php";
		if (file_exists($path)) {
			require_once $path;
		}
	}
});
spl_autoload_register(function($cls) {
	if (preg_match("/^CMS_/", $cls)) {
		$table = strtolower(str_replace("CMS_", "", $cls));
		eval("class {$cls} extends CMS_Table {
				public static \$table = \"$table\";
				public static \$inAdmin = true;
				public static \$enableAdminNew = true;
				public static \$enableAdminEdit = true;
				public static \$enableAdminDelete = true;
				public static \$enableSearch = false;
				public static \$searchCols = array();
				public static \$searchShadowExists = false;
				public static \$searchShadowTable ;
				protected static \$metadata = array();
				protected static \$metaLoaded = false;
		
				public function __construct(\$id = false) {
					parent::__construct(\$id);
				}
			}
		");
	}
});