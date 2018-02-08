<?php

class Module extends AppData {

	protected $table = 'module';

	public function fetchUserModules($user) {
		$sql = "SELECT {$this->tableName()}.* FROM {$this->tableName()} INNER JOIN `ac_user_module` ON `ac_user_module`.`module_id`={$this->tableName()}.`id` INNER JOIN `ac_user` ON `ac_user`.`id`=`ac_user_module`.`user_id` WHERE `ac_user`.`public_id`=:userid order by `ac_user`.`default_module`, `ac_module`.`name` ASC";
		$params[':userid'] = $user;
		return $this->fetchAll($sql, $params);
	}

	public function fetchDefaultModule() {
		$params[":default_module"] = auth()->getRecord()->default_module;
		$sql = "SELECT m.* FROM {$this->tableName()} m WHERE m.id = :default_module";
		return $this->fetchOne($sql, $params);
	}

}
