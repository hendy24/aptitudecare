<?php

class Group extends AppModel {

	protected $table = 'group';


	public function fetchModule() {
		$sql = "SELECT * FROM group_module WHERE group_id = :id";
		$params[":id"] = $this->id;

		return $this->fetchAll($sql, $params);
	}

	public function fetchModules($group_id) {
		$sql = "SELECT module.id, module.name FROM group_module INNER JOIN module ON module.id = group_module.module_id WHERE group_id =:id";
		$params[":id"] = $group_id;
		return $this->fetchAll($sql, $params);
	}
	
}