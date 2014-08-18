<?php

class Location extends AppModel {
	
	public $table = 'location';
	
	public function fetchLocations() {
		$user = auth()->getRecord();
		$module = $user->module_name;
		$sql = "SELECT {$this->table}.* FROM {$this->table} 
			INNER JOIN `user_location` ON `user_location`.`location_id`={$this->table}.`id` 
			INNER JOIN `modules_enabled` ON `modules_enabled`.`location_id`={$this->table}.`id` 
			INNER JOIN `module` ON `module`.`id`=`modules_enabled`.`module_id`
			WHERE `user_location`.`user_id`=:userid
			AND `module`.`name`=:module";
		$params = array(
			':userid' => $user->id,
			':module' => $module
		);
		
		return $this->fetchAll($sql, $params);
	}
	
	
	public function fetchLocation($id) {
		$sql = "SELECT * FROM {$this->table} WHERE {$this->table}.";
		
		if (is_numeric($id)) {
			$sql .= "`id`=:id";
		} else {
			$sql .="`public_id`=:id";
		}
		
		$params[':id'] = $id;
		return $this->fetchOne($sql, $params);
	}

	public function fetchLocationStates() {
		$sql = "(SELECT `{$this->table}`.`state` FROM `{$this->table}` WHERE `{$this->table}`.`id` = :id) UNION (SELECT  `location_link_state`.`state` FROM `{$this->table}` INNER JOIN `location_link_state` ON `location_link_state`.`location_id` = `{$this->table}`.`id` WHERE `{$this->table}`.`id` = :id)";
		$params[':id'] = $this->id;
		$result = $this->fetchAll($sql, $params);
	}
}