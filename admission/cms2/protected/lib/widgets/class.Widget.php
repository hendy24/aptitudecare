<?php
abstract class Widget {

	protected $properties = array(
		"options" => array(),
		"model" => false,
		"fieldname" => false,
		"multi_idx" => 0,
		"value" => false,
		"css_class" => false,
		"label" => false,
		"instructions" => false
	);

	public function __construct($obj) {
		$this->properties["model"] = $obj;
		$this->properties["modelTitle"] = $obj::getModelTitle();
	}

	public function __set($key, $value) {
		$this->properties[$key] = $value;
	}

	public function __get($key) {
		return $this->properties[$key];
	}

	public function getName() {
		return "record[" . $this->fieldname . "]";
	}

	public function getID() {
		return $this->multi_idx . "_" . $this->fieldname;
	}
	
	public function setProperty($key, $value) {
		$this->{$key} = $value;
	}
	
	public function setOption($key, $value) {
		$this->properties["options"][$key] = $value;
	}

	public abstract function render();

	public function jquery() {

	}

	public function jqueryReady() {
		
	}
}

