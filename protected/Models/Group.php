<?php

class Group extends AppModel {

	protected $table = 'group';


	public function fetchModule() {
		$sql = "SELECT * FROM group_module WHERE group_id = :id";
		$params[":id"] = $this->id;
		return $this->fetchOne($sql, $params);
	}
	
}