<?php

class MenuChange extends Dietary {

	protected $table = "menu_change";


	public function fetchExisting($menu_item_id, $location_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE menu_item_id = :menu_item_id AND location_id = :location_id";
		$params[":menu_item_id"] = $menu_item_id;
		$params[":location_id"] = $location_id;

		$result = $this->fetchOne($sql, $params);

		if (empty ($result)) {
			return $this;
		} else {
			return $result;
		}
	}
	
}