<?php

/*
 * 
 * (c) Intercarve Networks LLC, all rights reserved.
 * 
 */

class Input extends Singleton {

	private $_post = array();
	private $_get = array();
	private $all = array();
	private $historyHold = false;

	public function init() {
		foreach ($_REQUEST as $key => $value) {
			if (! is_array($value)) {
				$this->all[$key] = stripslashes($value);
				if (isset($_POST[$key])) {
					$this->_post[$key] = stripslashes($value);
				}
				elseif (isset($_GET[$key])) {
					$this->_get[$key] = stripslashes($value);
				}
			} else {
				$this->all[$key] = stripslashes_deep($value);
				if (isset($_POST[$key])) {
					$this->_post[$key] = stripslashes_deep($value);
				}
				elseif (isset($_GET[$key])) {
					$this->_get[$key] = stripslashes_deep($value);
				}
			}
		}

		// If secure_form was enabled (just set class="secure-form" on your <form> element)
		if (isset($this->all["_secure_form"]) && $this->all["_secure_form"] == 1 && isset($this->all["_secure_form_timestamp"])) {
			// This should have been set when the AJAX got the timestamp.
			if ($_SESSION[APP_NAME]["_secure_form"]["salt"] != '') {
				// So was this
				if (isset($_COOKIE["_secure_form_token"])) {
					// Make sure the hash matches
					if ($_COOKIE["_secure_form_token"] == md5($_SESSION[APP_NAME]["_secure_form"]["salt"] . $this->all["_secure_form_timestamp"])) {
						// Make sure the time hasn't expired. We default to 10 minutes.
						if ((int) $this->all["_secure_form_timestamp"] + (10 * 60) < mktime()) {
							feedback()->error("You took too long to fill out the form. Please try again.");
						}
					} else {
						feedback()->error("We are unable to process your request as this time. Please try again.");
					}

					// Clear the cookie
					setcookie("_secure_form_token", '', -10000, '/', COOKIE_DOMAIN);
				}
			}
		}
		
	}


	public function storeHistory($name) {
		// this instructs the front controller to leave history vals in the session. 
		$this->historyHold = true;
		$_SESSION[APP_NAME]["input"]["_history_vals"][$name] = (Array) $this->all;
	}
	
	public function injectHistory($name, $var, $val) {
		$_SESSION[APP_NAME]["input"]["_history_vals"][$name][$var] = $val;
	}

	public function clearHistory($name) {
		$_SESSION[APP_NAME]["input"]["_history_vals"][$name] = array();
	}
	
	public static function hasHistory($name) {
		return isset($_SESSION[APP_NAME]["input"]["_history_vals"][$name]) && count($_SESSION[APP_NAME]["input"]["_history_vals"][$name]) > 0;
	}
	
	public function getHistory($name = false) {
		if ($name !== false) {
			return (isset($_SESSION[APP_NAME]["input"]["_history_vals"][$name])) ? $_SESSION[APP_NAME]["input"]["_history_vals"][$name] : array();
		} else {
			return (isset($_SESSION[APP_NAME]["input"]["_history_vals"])) ? $_SESSION[APP_NAME]["input"]["_history_vals"] : array();
		}
	}	
	public function setHistoryHold($bool) {
		$this->historyHold = $bool;
	}
	
	public function getHistoryHold() {
		return $this->historyHold;
	}

	public function __get($name) {
		if (isset($this->all[$name])) {
			return $this->all[$name];
		} else {
			return '';
		}
	}

	public function post($name = false) {
		if ($name !== false)
			return $this->_post[$name];
		else
			return $this->_post;
	}

	public function get($name = false) {
		if ($name == false)
			return $this->_get[$name];
		else
			return $this->_get;
	}


}