<?php

abstract class Singleton {
	protected static $instance = array();

	// overload this with arguments to pass to init()
	public static function getInstance() {
		$cls = get_called_class();
		if (! isset(static::$instance[$cls]) ) {
			$obj = static::$instance[$cls] = new $cls;
			call_user_func_array(array($obj, 'init'), func_get_args());
		}
		return static::$instance[$cls];
	}

	// prevent definition of a constructor
	final private function  __construct() { }

	abstract public function init() ;
}