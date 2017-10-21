<?php

class Authentication_Admin_Base extends Authentication {

	protected $table = 'admin_user';
	protected static $table_exists = false;
	protected $usernameField = 'email';
	protected $passwordField = 'password';
	protected $cookie_name = "authentication_admin_record";


	public function init() {
		// Create an empty Admin_User and Admin_Rol objs so that their tables are initialized if it didn't already exist.
		$obj = new CMS_Admin_User();
		$obj = new CMS_Admin_Role();
		parent::init();
	}

	// the admin version has unencrypted passwords because clients generally forget theirs, and it's easier for me to tell them what it is,
	// than to have them go through a retrieval process.
	public static function encryptPassword($password_clear) {
		return $password_clear;
	}

	public function login($username, $password_clear, $override = false) {
		parent::login($username, $password_clear, $override);
		if ($this->valid()) {
			$this->record->datetime_seen = datetime();
			$this->record->save();
			$this->writeToSession();
		}
	}

	public function isRoot() {
		return $this->record->root == 1;
	}
}