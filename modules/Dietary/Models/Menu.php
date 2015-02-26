<?php

class Menu extends Dietary {

	protected $table = "menu";

	public function fetchMenu($location_id, $start_date) {
		$sql = "SELECT * FROM {$this->tableName()} INNER JOIN dietary_location_menu ON dietary_location_menu.menu_id = {$this->tableName()}.id WHERE dietary_location_menu.location_id = :location_id AND dietary_location_menu.date_start <= :start_date ORDER BY dietary_location_menu.date_start DESC LIMIT 1";
		$params[":location_id"] = $location_id;
		$params[":start_date"] = $start_date;
		return $this->fetchOne($sql, $params);
	}

}