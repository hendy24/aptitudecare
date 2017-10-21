<?php
class CMS_Simple_Content extends CMS_Table {

	public static $table = "simple_content";
	public static $modelTitle = 'Simple Website Content';
	protected static $enableCreateStructure = true;
	public static $inAdmin = true;
	public static $enableAdminNew = "root";
	public static $enableAdminEdit = true;
	public static $enableAdminDelete = "root";


	//public function __construct($val = false, $multi_idx = 0) {
	//	parent::__construct();
	//	$this->load($val);
	//}

	public function getTitle() {
		return $this->title;
	}

	protected static $metadata = array(

		"name" => array(
			"label" => "Name",
			"widget" => "text",
			"instructions" => "",
			"options" => array (
				"readOnly" => true
			)
		),
		"title" => array(
			"label" => "Title",
			"widget" => "text",
			"instructions" => "",
			"options" => array (
				"size" => 50
			)
		),
		"value" => array(
			"label" => "Value",
			"widget" => "text"
		)
	);


	public function load($val) {
		if (is_numeric($val)) {
			$this->loadByID($val);
		} else {
			$this->loadByName($val);
		}
		$this->initActions();
	}

	public function loadByName($name) {
		$row = db()->getRow(static::$table, array("name" => $name), "std");
		$this->setRecord($row);
	}

	public function loadByID($id) {
		$this->setRecord(db()->getRow(static::$table, array("id" => $id), "std"));
	}

	public function nameIsUsed($name) {
		$records = $this->fetch(array("name" => $name));
		return count($records) > 0;
	}
	
	public static function loadSimple($name, $title='', $seed='') {
		$obj = static::generate();
		$obj->loadByName($name);
		if (! $obj->valid() ) {
			unset($obj);
			$obj = static::generate();
			$obj->name = $name;
			if ($title == '') {
				$obj->title = ucfirst(str_replace("_", " ", $name));
			} else {
				$obj->title = $title;
			}
			if ($seed != '') {
				$obj->value = $seed;
			}
			$obj->save();
		}
		return $obj;
	}

}
