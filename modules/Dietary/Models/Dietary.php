<?php

class Dietary extends AppModel {

	protected $prefix = "dietary";

	public function fetchByName($name = false) {
		if ($name) {
			$sql = "SELECT * FROM {$this->tableName()} WHERE name = :name LIMIT 1";
			$params[":name"] = $name;
			return $this->fetchOne($sql, $params);
		}

		return false;
	}

	
}