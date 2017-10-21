<?php

class CMS_Reports extends CMS_Table {
		
	public static function fetchNames() {
		$sql = "SELECT * from reports order by description asc";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
}