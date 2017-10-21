<?php

	class CMS_Facility_Link_States extends CMS_Table {
		
		public static $table = "facility_link_states";
		
		public static function getAdditionalStates($facility_id) {
			$params[":id"] = $facility_id;
			
			$sql = "SELECT * FROM facility_link_states WHERE facility_id = :id";
			
			$obj = static::generate();
			return $obj->fetchCustom($sql, $params);
		}
	
	}