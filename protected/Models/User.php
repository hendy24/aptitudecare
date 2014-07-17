<?php

class User extends Model {
	public $table = 'users';
	public $username_field = 'email';
	public $password_field = 'password';

	public function validateUser($username, $password) {
		// Need to salt and encrypt password
		$enc_password = password_hash($password, PASSWORD_DEFAULT);
								
		// Check database for username and password
		$sql = "select * from `{$this->table}` inner join mods on mods.id={$this->table}.default_module where `{$this->username_field}`=:username ";
		$params = array(
			":username" => $username,
		);
		
		$user =  $this->fetchRow($sql, $params);;
		
		// check if returned user matches password
		if (password_verify($password, $user->password)) {
			return $user;
		} 
		
		return false;
	}


}