<?php

class MenuItem extends Dietary {

	protected $table = "menu_item";

	public function fetchMenuDay($menu_id = false) {
		if ($menu_id) {
			$params[":menu_id"] = $menu_id;
			$sql = "SELECT MAX({$this->tableName()}.day) AS count FROM {$this->tableName()} WHERE {$this->tableName()}.menu_id = :menu_id";
			return $this->fetchOne($sql, $params);
		}

		return false;
	}


	public function fetchMenuItems($location_id, $start_date, $end_date, $start_day, $end_day, $menu_id) {

		$params = array(
			":location_id" => $location_id,
			":start_date" => $start_date,
			":end_date" => $end_date,
			":start_day" => $start_day,
			":end_day" => $end_day,
			":menu_id" => $menu_id
		);


		// fetch classes for join tables
		$menuMod = $this->loadTable('MenuMod');
		$menuChange = $this->loadTable('MenuChange');

		// set the fields to fetch
		$sql = "SELECT 
					COALESCE({$menuMod->tableName()}.id, {$menuChange->tableName()}.id, {$this->tableName()}.id) as id, 
					COALESCE({$menuMod->tableName()}.public_id, {$menuChange->tableName()}.public_id, {$this->tableName()}.public_id) AS public_id, 
					COALESCE({$menuMod->tableName()}.menu_item_id, {$menuChange->tableName()}.menu_item_id) AS menu_item_id,
					dietary_menu_item.menu_id,
					dietary_menu_item.meal_id,
					dietary_menu_item.day,
					dietary_menu_mod.date,
					COALESCE({$menuMod->tableName()}.content, {$menuChange->tableName()}.content, {$this->tableName()}.content) AS content 
	
				FROM {$this->tableName()} 
				LEFT JOIN {$menuMod->tableName()} ON ({$menuMod->tableName()}.menu_item_id = {$this->tableName()}.id AND {$menuMod->tableName()}.location_id = :location_id AND {$menuMod->tableName()}.date BETWEEN :start_date AND :end_date) 
				LEFT JOIN {$menuChange->tableName()} ON ({$menuChange->tableName()}.menu_item_id = dietary_menu_item.id AND {$menuChange->tableName()}.location_id = :location_id) 
	
				WHERE (({$this->tableName()}.day BETWEEN :start_day AND :end_day) AND ({$this->tableName()}.menu_id = :menu_id)) ORDER BY {$this->tableName()}.id ASC";
			

		// return the results
		return $this->fetchCustom($sql, $params);
	}


	public function paginateMenuItems($menu_id, $location_id, $page = false) {

		$params = array(
			":location_id" => $location_id,
			":menu_id" => $menu_id
		);

		// fetch classes for join tables
		$menuMod = $this->loadTable('MenuMod');
		$menuChange = $this->loadTable('MenuChange');

		$sql = "SELECT 
					COALESCE({$menuChange->tableName()}.id, {$this->tableName()}.id) as id, 
					COALESCE({$menuChange->tableName()}.public_id, {$this->tableName()}.public_id) AS public_id, 
					COALESCE({$menuChange->tableName()}.menu_item_id) AS menu_item_id,
					{$this->tableName()}.menu_id,
					{$this->tableName()}.meal_id,
					{$this->tableName()}.day,
					COALESCE({$menuChange->tableName()}.content, {$this->tableName()}.content) AS content 
	
				FROM {$this->tableName()} 
				LEFT JOIN {$menuChange->tableName()} ON ({$menuChange->tableName()}.menu_item_id = dietary_menu_item.id AND {$menuChange->tableName()}.location_id = :location_id) 
	
				WHERE {$this->tableName()}.menu_id = :menu_id ORDER BY {$this->tableName()}.id ASC";

		$pagination = new Paginator();
		$pagination->default_ipp = 21;
		return $pagination->paginate($sql, $params, $this, $page);
	}

}