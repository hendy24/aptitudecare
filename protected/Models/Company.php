<?php

class Company extends Model {
	
	public $table = 'company';
	
	public static function getEmailExt() {
		$sql = "select * from `company` where id=1";
		
		$query = static::generate();
		return $query->fetchOne($sql);
	}
	
}