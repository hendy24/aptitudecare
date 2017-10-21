<?php

class Input {
	
	public $input = array();
	public $_post = array();
	public $_get = array();	
	public $page = null;
	public $action = null;
	public $item = null;
	public $object = null;
	public $colName = null;
	public $value = null;


	public function __construct() {
		foreach ($_REQUEST as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					if (is_array($v) && !empty($v)) {
						$this->$key->$k = $v;
					} else {
						$this->$key->$k = stripslashes($v);
					}
					
				}
			} elseif (isset ($_POST[$key])) {
				$this->$key = stripslashes($value);
			} elseif (isset ($_GET[$key])) {
				$this->$key = stripslashes($value);
			}
		}

	}
	
	public function is($data) {
		if ($data == 'post') {
			if ($_POST) {
				return true;
			}
		}
	}
	
	public function post($name = false) {
		if ($name != false) {
			return $this->_post[$name];
		} else {
			return $this->_post;
		}
	}
	
	public function get($name = false) {
		if ($name != false) {
			return $this->_get[$name];
		} else {
			return $this->_get;
		}
	}
	
}