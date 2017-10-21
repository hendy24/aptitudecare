<?php

abstract class Singleton {
	protected static $instances = array();
	
	public static function getInstance() {
		$class = get_called_class();
		
		if (!isset (static::$instances[$class])) {
			$obj = static::$instances[$class] = new $class();
			call_user_func_array(array($obj, 'init'), func_get_args());
		}
				
		return static::$instances[$class];
	}
	
	final private function __construct() { }
	private function __clone() { }
	public function __wakeup() {
		throw new Exception('Cannot unserialize singleton');
	}
	
	
}