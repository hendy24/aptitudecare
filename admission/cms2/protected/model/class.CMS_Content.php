<?php
class CMS_Content extends CMS_Table {

	public static $table = "content";
	public static $modelTitle = 'Website Content';
	protected static $enableCreateStructure = true;
	public static $inAdmin = true;
	public static $enableAdminNew = "root";
	public static $enableAdminEdit = true;
	public static $enableAdminDelete = "root";


	public function __construct($val = false, $multi_idx = 0) {
		parent::__construct();
		$this->load($val);
	}

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
		)
	);

	public function __get($key) {
		if (preg_match("/^image([0-9]{1,2})/", $key, $matches)) {
			$idx = $matches[1];
			return $this->record->{"file{$idx}"};
		} elseif ($key == 'content' || $key == 'copy') {
			if ($this->record->content != '') {
				return $this->record->content;
			} elseif ($this->record->copy != '') {
				return $this->record->copy;
			}
		} else {
			return $this->record->{$key};
		}
	}

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
	
//	public static function 
}
