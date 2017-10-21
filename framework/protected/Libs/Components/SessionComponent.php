<?php

class Session extends Singleton {		
	
	public $messages;
	public $module;
	public $referring_page;


	public function init() {
		if (isset ($_SESSION[APP_NAME])) {
			foreach ($_SESSION[APP_NAME] as $k => $v) {
				$this->$k = $v;
			}
		}
	}
	
	
	
	
	
	public function getSessionRecord($item) {
		return $this->$item;
		
		return false;
		
	}
	
	
	public function setVals($vals = array()) {
		if (!empty ($vals)){
			foreach ($vals as $k => $v) {
				$_SESSION[APP_NAME][$k] = $v;

			}	
		}
				
	}



	public function getModule() {
		if (isset ($_SESSION['current_module'])) {
			return $_SESSION['current_module'];
		}
		return false;
	}
	

	public function setModule($module = false) {
		if ($module) {
			$_SESSION['current_module'] = $module;
			$this->module = $module;
		}
	}
	
	
	
	/*
	 * -------------------------------------------
	 * FLASH MESSAGE FUNCTIONS
	 * -------------------------------------------
	 *
	 */
	
	
	public function setFlash($messages = array(), $class = false) {
		if (is_array($messages)) {
			foreach ($messages as $k => $m) {
				if (is_array($m)) {
					$key = 100;
					foreach ($m as $message) {
						$_SESSION['messages'][$class][$key] = $message;
						$key++;
					}
				} else {
					$_SESSION['messages'][$class][$k] = $m;
				}
				
			}	
		} else {
			$_SESSION['messages'][$class] = $messages;
		}
		$this->messages = $_SESSION['messages'];
			
	}

	public function setReferringPage($url = null) {
		if ($url != null) {
			$_SESSION['referring_page'] = $url;
		} else {
			$_SESSION['referring_page'] = $_SERVER['HTTP_REFERER'];
		}
		$this->referring_page = $_SESSION['referring_page'];
	}

	public function getReferringPage() {
		return $this->referring_page;
	}
		
	public function displayData($name) {
		return $this->checkData($name);
	}
	
	public function checkFlashMessages() {
		if (isset ($_SESSION['messages'])) {
			foreach ($_SESSION['messages'] as $type => $messages) {
				if (is_array($messages)) {
					foreach ($messages as $k => $m) {
						$this->messages['messages'][$type][$k] = $m;
					}
				} else {
					$this->messages['messages'][$type] = $messages;
				}
				
				
				$_SESSION['messages'] = null;
			}
			
			smarty()->assignByRef('flashMessages', $this->messages['messages']);
		} 
		
		return false;
		
	}	
	
	private function checkData($name) {
		if (isset ($_SESSION[$name])) {
			$data = $_SESSION[$name] ;
			unset ($_SESSION[$name]);
		} else {
			$data = '';
		}
		return $data;
	}
	
	public function messageIsSet() {
		if (isset ($_SESSION['message'])) {
			return true;
		} else {
			return false;
		}
	}
	
	
	
}
