<?php

class CMS_Company extends CMS_Table {
	
	public static $table = "company";
	public static $modelTitle = "Companies";
	
	public static function getEmailExt() {
		$sql = "select global_email_ext from company where id = 1";
		
		$obj = static::generate();
		$result = $obj->fetchCustom($sql);
		return $result[0]->global_email_ext;
	}
 
}