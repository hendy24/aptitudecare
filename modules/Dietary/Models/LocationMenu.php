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

	public function fetchCurrent($location_id, $date) {
		$menu = $this->loadTable("Menu");
		$sql = "SELECT l.*, m.public_id, m.name FROM {$this->tableName()} l INNER JOIN {$menu->tableName()} AS m ON m.id = l.menu_id WHERE l.location_id = :location_id AND l.date_start < :date ORDER BY l.date_start DESC LIMIT 1";
		$params = array(":location_id" => $location_id, ":date" => $date);
		return $this->fetchOne($sql, $params);
	}


	public function checkExisting($menu_id, $location_id) {
		$sql = "SELECT * FROM {$this->tableName()} lm WHERE lm.menu_id = :menu_id AND lm.location_id = :location_id LIMIT 1";
		$params = array("menu_id" => $menu_id, ":location_id" => $location_id);
		$result = $this->fetchOne($sql, $params);
		if (!empty ($result)) {
			return $result;
		} else {
			return $this;
		}
	}
}