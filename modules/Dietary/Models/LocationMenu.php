<?php

class LocationMenu extends Dietary {

	protected $table = "location_menu";

	public function fetchMenu($location_id, $date) {
		$menu = $this->loadTable('Menu');
		$sql = "SELECT {$this->tableName()}.*, {$menu->tableName()}.name FROM {$this->tableName()} INNER JOIN {$menu->tableName()} ON {$menu->tableName()}.id = {$this->tableName()}.menu_id WHERE {$this->tableName()}.location_id = :location_id AND {$this->tableName()}.date_start <= :date ORDER BY {$this->tableName()}.date_start DESC";
		$params = array(
			":location_id" => $location_id,
			":date" => $date
		);

		return $this->fetchOne($sql, $params);
	}
	
	public function fetchAvailable($location_id) {
		$menu = $this->loadTable('Menu');
		$sql = "SELECT * FROM {$this->tableName()} INNER JOIN {$menu->tableName()} ON {$menu->tableName()}.id = {$this->tableName()}.menu_id WHERE {$this->tableName()}.location_id = :location_id";
		$params[":location_id"] = $location_id;

		return $this->fetchAll($sql, $params);
	}
}