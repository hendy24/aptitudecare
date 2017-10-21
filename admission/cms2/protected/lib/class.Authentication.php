<?php

class Authentication extends Singleton {

	protected $table = 'site_user';
	protected $usernameField = 'email';
	protected $passwordField = 'password';
	protected $record = false;

	protected static $allowed = true;
	protected $cookie_name = "authentication_record";

	public function init() {
		if ( isset($_SESSION[APP_NAME][$this->cookie_name]) ) {
			$this->getFromSession();
		} else {
			$this->writeToSession();
		}
		// We may *think* we're logged in, but we always need to hit the DB and doublecheck.
		if (! $this->checkRecord()) {
			$this->record = false;
			$this->writeToSession();
		} else {
			// load record from the DB so we're always up to date.
			$this->loadRecord();
			$this->writeToSession();
		}
	}
	
	public static function disallow() {
		static::$allowed = false;
		$auth = static::getInstance();
		if (! $auth->valid() ) {
			throw new AuthenticationDisallowedException();
		}
	}
	
	public static function allow() {
		static::$allowed = true;
	}
	
	public static function isAllowed() {
		return static::$allowed;
	}

	protected function checkRecord() {
		if ($this->valid()) {
			$sql = "select * from `{$this->table}` where
			`{$this->usernameField}`=:username";
			$params = array(
				":username" => $this->record->{$this->usernameField}
			);
			$record = db()->getRowCustom($sql, $params, Model::clsname($this->table));

			if ($record == false) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	public function loadRecord() {
		if ($this->valid()) {
			$sql = "select * from `{$this->table}` where
			`{$this->usernameField}`=:username";
			$params = array(
				":username" => $this->record->{$this->usernameField}
			);
			$record = db()->getRowCustom($sql, $params, Model::clsname($this->table));

			if ($record == false) {
				return false;
			} else {
				$this->record = $record;
				return true;
			}
		} else {
			return false;
		}
	}

	public function writeToSession() {
		$_SESSION[APP_NAME][$this->cookie_name] = $this->record->id;
	}

	protected function getFromSession() {
		//$this->record = unserialize($_SESSION[APP_NAME][$this->cookie_name]);
		$sql = "select * from `{$this->table}` where
		`id`=:id";
		$params = array(
			":id" => $_SESSION[APP_NAME][$this->cookie_name]
		);
		
		$this->record = db()->getRowCustom($sql, $params, Model::clsname($this->table));
	}

	public function __get($key) {
		return $this->record->{$key};
	}

	public function getRecord() {
		return $this->record;
	}

	public function getUsername() {
		return $this->record->{$this->getUsernameField()};
	}

	public static function encryptPassword($str) {
		if ($str == '') {
			throw new Exception("Password cannot be empty.");
		}

		return md5(sha1(strrev($str)));
	}

	public function valid() {
		if ($this->record !== false) {
			return true;
		}
		return false;
	}

	public function lookupByUsername($username) {
		$sql = "select * from `{$this->table}` where
		`{$this->usernameField}`=:username";

		$params = array(
			":username" => $username
		);
		$record = db()->getRowCustom($sql, $params, Model::clsname($this->table));
		return $record;
	}

	public function login($username, $password_clear, $override = false) {
		if ($override == true) {
			$record = $this->lookupByUsername($username);
		} else {
			$password_encr = static::encryptPassword($password_clear);
			$sql = "select * from `{$this->table}` where
			`{$this->usernameField}`=:username and
			`{$this->passwordField}`=:password";
			$params = array(
				":username" => $username,
				":password" => $password_encr
			);
			$record = db()->getRowCustom($sql, $params, Model::clsname($this->table));
		}
		if ($record == false) {
			$this->record = false;
			$this->writeToSession();
			return false;
		} else {
			$this->record = $record;
			$this->writeToSession();
			return true;
		}


	}

	public function logout() {
		$this->record = false;
		$this->writeToSession();
	}
}
