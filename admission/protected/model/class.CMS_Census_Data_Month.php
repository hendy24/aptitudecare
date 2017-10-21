<?php

class CMS_Census_Data_Month extends CMS_Table {
	public static $table = "census_data_month";
	
	public static function clearTable() {
		db()->query("delete from census_data_month");
		return true;
	}
	
	public static function fetchCensusVals() {
		$sql = "SELECT * FROM `census_data_month`";
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
	
	public static function saveDayCensusData($f = false, $c = false, $d = false) {
		$obj = new CMS_Census_Data_Month();
		$obj->facility_id = $f;
		$obj->census_value = $c;
		$obj->datetime = $d;
		$obj->save();
		return true;
	}	
	
	public static function fetchCurrentCensus($facility = false) {
		$sql = "select round(avg(census_data_month.census_value), 2) as adc, adc.goal from census_data_month inner join adc on adc.facility = census_data_month.facility_id where census_data_month.facility_id = :facility";
		$params["facility"] = $facility;
		$obj = static::generate();
		return $obj->fetchCustom($sql, $params);
	}
	
	public static function calcCensusForMonth() {
		$sql = "select round(avg(census_data_month.census_value), 2) as adc, facility_id from census_data_month group by facility_id";
		
		$obj = static::generate();
		return $obj->fetchCustom($sql);
	}
	
	
}