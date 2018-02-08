<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class UserModule extends AppData {

	protected $table = "user_module";


	public function fetchAssignedModules($user_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE user_id = :user_id";
		$params[":user_id"] = $user_id;
		return $this->fetchAll($sql, $params);
	}





} // END class UserModule extends AppModule 