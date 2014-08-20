<?php

class Module extends AppModel {
	
	protected $table = 'module';

	public function fetchUserModules($user) {
		$sql = "SELECT {$this->table}.* FROM {$this->table} INNER JOIN `user_module` ON `user_module`.`module_id`={$this->table}.`id` INNER JOIN `user` ON `user`.`id`=`user_module`.`user_id` WHERE `user`.`public_id`=:userid order by `user`.`default_module`, `module`.`name` ASC";
		$params[':userid'] = $user;
		return $this->fetchAll($sql, $params);
	}
	
}