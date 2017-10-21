<?php


class Carry extends Singleton {
	private $vals = array();
	private $cookiename;

	public function init() {
		if (! isset($_SESSION[APP_NAME]["carry"])) {
			$_SESSION[APP_NAME]["carry"] = array();
		}

		$this->vals =& $_SESSION[APP_NAME]["carry"];
		
		$this->cookiename = APP_NAME . "_carry";
		$this->awaken_with_cookie();
	}

	public function dump() {
		vd($this);
	}

	public function exists($key) {
		return isset($this->vals[$key]);
	}
	
	public function __set($key, $value) {
		$this->vals[$key] = $value;
		$this->store_cookie();
	}

	public function __get($key) {
		return $this->vals[$key];
	}

	public function clear($key) {
		unset($this->vals[$key]);
		$this->store_cookie();
	}

	public function clearAll() {
		$this->vals = array();
		$this->store_cookie();
	}

	/*
	public function store($page, $name, $value) {
		if (! isset($this->vals["$page"])) {
			$this->vals["$page"] = array();
		}
		$this->vals["$page"]["$name"] = $value;
		$this->store_cookie();
		return true;
	}
	
	public function retrieve($page, $name, $clear = false) {
		$this->awaken_with_cookie();
		if (! isset($this->vals["$page"]["$name"]) ) {
			return false;
		}
		$ret = $this->vals["$page"]["$name"] ;
		if ($clear == true) {
			$this->clear_item($page, $name);
		}
		return $ret;
	}
	
	
	public function clear_item($page, $name) {
		if (! isset($this->vals["$page"]["$name"]) ) {
			// Doesn't exist, nothing to do
			return true;
		}
		unset($this->vals["$page"]["$name"]);
		$this->store_cookie();
		return true;
	}
	public function clear_page($page) {
		if (! isset($this->vals["$page"]) ) {
			// Doesn't exist, nothing to do
			return true;
		}
		unset ($this->vals["$page"]);
		$this->store_cookie();
		return true;
	}
	public function clear_all() {
		$this->vals = array();
		$this->store_cookie();
	}
	
	public function page_exists($page) {
		if (isset($this->vals["$page"])) {
			return true;
		} else {
			return false;
		}
	}

	public function item_exists($page, $name) {
		if (isset($this->vals["$page"]["$name"])) {
			return true;
		} else {
			return false;
		}
	}
	*/
	public function store_cookie() {
		// Cookie string: <expire_time>|<expire_last_updated>|<serialized_data>
		/*
		$now = time();
		$expire = time()+(60*60*24*7);
		$svals = serialize($this->vals);
		
		$cookie_str = "$now|$expire|$svals";
		
		$_COOKIE[$this->cookie_name] = $cookie_str;
		return setcookie("$this->cookiename", $cookie_str, $expire, "/", COOKIE_DOMAIN);
		*/
	}
	
	public function read_cookie() {
		/*
		if ($_COOKIE["$this->cookiename"] == "") {
			return false;
		} else {
			$cookie_str = $_COOKIE["$this->cookiename"];
			preg_match("/(.*)\|(.*)\|(.*)/", $cookie_str, $matches);
			
			$vals = array(
				"expires" => $matches[1], 
				"expires_last_updated" => $matches[2], 
				"vals" => unserialize(stripslashes($matches[3])),
			);
			return $vals;
		}
		*/
	}
	
	public function awaken_with_cookie() {
		/*
		$cookievals = $this->read_cookie();
		if (count($this->vals) == 0) {
			$this->vals = $cookievals["vals"];
		}
		*/
	}
}