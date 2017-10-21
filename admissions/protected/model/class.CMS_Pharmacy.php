<?php

 class CMS_Pharmacy extends CMS_Table {
	 
	 public static $table = "pharmacy";
	 public static $modelTitle = "Pharmacies";
	 
	 
	 
	 
	 
	 public function findPharmacies($state = null) {
		$sql = "select * from pharmacy";
		
		if ($state != '') {
			$params[":state"] = $state;
			$sql .= "  where state = :state";
		}
		return $this->fetchCustom($sql, $params);
	}
	
	
	public function deletePharmacy($id) {
		db()->query("delete from pharmacy where id=:id", array(
			":id" => $id
		));
		
		return true;
	}


	 
 }