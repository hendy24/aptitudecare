<?php

class Feedback extends Singleton {
	private $vals;
	private $readstatus;
	
	public function init() {
		if (! isset($_SESSION[APP_NAME]["feedback"]) ) {
			$_SESSION[APP_NAME]["feedback"] = array("error" => array(), "warning" => array(), "conf" => array(), "debug" => array());
		}
		$this->vals = &$_SESSION[APP_NAME]["feedback"];
		$this->readstatus = 0;
	}

	public function dump() {
		vd($this);
	}

	public function getVals($type) {
		if (array_key_exists($type, $this->vals)) {
			return $this->vals[$type];
		}
		return false;
	}

	public function getReadStatus() {
		return $this->readstatus;
	}

	public function clear() {
		foreach ($this->vals as $type => $valarr) {
			$this->vals["$type"] = array();
		}
	}
	
	public function notify_new() {
		$this->readstatus = true;
	}
	
	public function notify_old() {
		$this->readstatus = false;
	}
	
	public function retrieve($action = "clear") {
		$vals = $this->vals;
		if ($action == "clear" && $action != "retain") {
			$this->clear();	
		}
		return $vals;
	}
		
	public function error($msg) {
		// sanity check
		if (! array_key_exists("error", $this->vals)) {
			return false;
		}

		// get the variable-length argument list
		$args = func_get_args();
		
		// anything after the first argument is an sprintf() arg
		$pargs = array_slice($args, 1);
		
		
		// put the message together: $msg = filtered pattern + unfiltered args, via sprintf().
		$msg = call_user_func_array("sprintf", array_merge(array(renderEscape($msg)), $pargs));

		array_push($this->vals["error"], $msg);
		if (input()->_history == "true" && input()->_history_name != '') {
			// history was explictly turned on.
			input()->storeHistory(input()->_history_name);
		}
	}
	public function warning($msg) {
		if (! array_key_exists("warning", $this->vals)) {
			return false;
		}
		// get the variable-length argument list
		$args = func_get_args();
		
		// anything after the first argument is an sprintf() arg
		$pargs = array_slice($args, 1);
		
		
		// put the message together: $msg = filtered pattern + unfiltered args, via sprintf().
		$msg = call_user_func_array("sprintf", array_merge(array(renderEscape($msg)), $pargs));
		array_push($this->vals["warning"], $msg);
	}
	public function conf($msg) {
		if (! array_key_exists("conf", $this->vals)) {
			return false;
		}
		// get the variable-length argument list
		$args = func_get_args();
		
		// anything after the first argument is an sprintf() arg
		$pargs = array_slice($args, 1);
		
		
		// put the message together: $msg = filtered pattern + unfiltered args, via sprintf().
		$msg = call_user_func_array("sprintf", array_merge(array(renderEscape($msg)), $pargs));
		array_push($this->vals["conf"], $msg);
	}
	public function debug($msg) {
		if (! array_key_exists("debug", $this->vals)) {
			return false;
		}
		// get the variable-length argument list
		$args = func_get_args();
		
		// anything after the first argument is an sprintf() arg
		$pargs = array_slice($args, 1);
		
		
		// put the message together: $msg = filtered pattern + unfiltered args, via sprintf().
		$msg = call_user_func_array("sprintf", array_merge(array(renderEscape($msg)), $pargs));
		array_push($this->vals["debug"], $msg);
	}
		
	public function wasError() {
		if (! array_key_exists("error", $this->vals)) {
			return false;
		}
		if (count($this->vals["error"]) > 0) {
			return true;
		} else {
			return false;
		}	
	}
	public function wasWarning() {
		if (! array_key_exists("warning", $this->vals)) {
			return false;
		}
		if (count($this->vals["warning"]) > 0) {
			return true;
		} else {
			return false;
		}	
	}
	public function wasConf() {
		if (! array_key_exists("conf", $this->vals)) {
			return false;
		}
		if (count($this->vals["conf"]) > 0) {
			return true;
		} else {
			return false;
		}	
	}

	
	
}