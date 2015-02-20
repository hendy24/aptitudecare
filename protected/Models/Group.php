<?php

class Group extends AppData {

	protected $table = 'group';


	public function fetchModule() {
		$sql = "SELECT * FROM ac_group_module WHERE group_id = :id";
		$params[":id"] = $this->id;

		return $this->fetchAll($sql, $params);
	}

	public function fetchModules($group_id) {
		$sql = "SELECT ac_module.id, ac_module.name FROM ac_group_module INNER JOIN ac_module ON ac_module.id = ac_group_module.module_id WHERE group_id =:id";
		$params[":id"] = $group_id;
		return $this->fetchAll($sql, $params);
	}
	
}