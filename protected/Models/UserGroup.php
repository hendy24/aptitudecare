<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class UserGroup extends AppData {

	protected $table = "user_group";

	public function fetchAssignedGroups($user_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE user_id = :user_id";
		$params[":user_id"] = $user_id;
		return $this->fetchAll($sql, $params);
	}






} // END clasUserGroup extends AppData  