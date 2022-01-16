<?php

class ModuleEnabled extends AppData {

	protected $table = "module_enabled";

	public static function isAdmissionsEnabled($location_id) {
		// $sql = "SELECT * FROM ac_module_enabled WHERE location_id = :location_id AND module_id = 1";
		// $params[":location_id"] = $location_id;

		// $result = static::fetchOne($sql, $params);

		// if (!empty ($result)) {
		// 	return true;
		// }

		// return false;
	}

}