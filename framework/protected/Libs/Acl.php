<?php

class Acl {
	
	private $db;
	private $userEmpty = false;
	
	// Initialize the db object
	public function __construct() {
		/* $this->db = new MySqlDb(); */
	}
	
	
	public function check ($permission, $user_id, $group_id) {
	
		// Check permissions first...
		if (!$this->user_permissions($permission, $user_id)) {
			return false;
		}
		
		if ($this->group_permissions($permission, $group_id) & $this->IsUserEmpty()) {
			return false;
		}
		
		return true;
	}
	
	
	public function user_permissions($permission, $user_id) {
		$sql = "SELECT COUNT(*) AS count from user_permissions WHERE permission_name = :permission AND user_id = :user_id";
		$params[':permission'] = $permission;
		$params[':user_id'] = $user_id;
		$c = db()->query($sql, $params);
		
		if ($c['count'] > 0) {
			$sql = "SELECT * FROM user_permissions WHERE permission_name = :permission AND user_id = :user_id";
			$result = db()->query($sql, $params);
			
			if ($result['permission_type'] == 0) {
				return false;
			}
			
			return true;
		}
		
		return true;	
		
	}
	
	
	public function group_permissions($permission, $group_id) {
		$sql = "SELECT COUNT(*) AS count from group_permissions WHERE permission_name = :permission AND group_id = :user_id";
		
		$params[':permission'] = $permission;
		$params[':group_id'] = $group_id;
		$c = db()->query($sql, $params);
		
		if ($c['count'] > 0) {
			$sql = "SELECT * FROM group_permissions WHERE permission_name = :permission AND group_id = :group_id";
			$result = db()->query($sql, $params);
			
			if ($result['permission_type'] == 0) {
				return false;
			}
			
			return true;
		}
		
		return true;	
		
	}
	
	
	public function setUserEmpty($val) {
		$this->userEmpty = $val;
	}
	
	public function isUserEmpty() {
		return $this->userEmpty;
	}
	
}