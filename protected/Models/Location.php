<?php

class Location extends Model {
	
	public $table = 'location';
	
	public function fetchLocations($user, $module) {
		$sql = "SELECT {$this->table}.* FROM {$this->table} 
			INNER JOIN `user_location` ON `user_location`.`location_id`={$this->table}.`id` 
			INNER JOIN `user` ON `user`.`id`=`user_location`.`user_id` 
			INNER JOIN `modules_enabled` ON `modules_enabled`.`location_id`={$this->table}.`id` 
			INNER JOIN `module` ON `module`.`id`=`modules_enabled`.`module_id`
			WHERE `user`.`public_id`=:userid
			AND `module`.`name`=:module";
		$params = array(
			':userid' => $user,
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
}