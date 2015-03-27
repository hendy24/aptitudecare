<?php

class Alternate extends Dietary {
	protected $table = "alternate";

	public function fetchAlternates($location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchOne($sql, $params);
	}
}