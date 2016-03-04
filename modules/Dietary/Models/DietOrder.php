<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class DietOrder extends Dietary {

	protected $table = "diet_order";
	protected $join = "diet_order_id";

	public function fetchDietInfo($patient_id) {
		$patient_diet_info = $this->loadTable('PatientDietInfo');
		$sql = "SELECT DISTINCT
			di.id,
			pdi.patient_id,
			CASE 
				WHEN di.is_other = 1 AND pdi.patient_id = :patient_id THEN di.name
				WHEN di.is_other = 0 THEN di.name 
			END AS name,
			di.is_other
		FROM {$this->tableName()} di
		LEFT JOIN {$patient_diet_info->tableName()} pdi ON pdi.diet_order_id = di.id
		GROUP BY di.id HAVING name IS NOT NULL";

		$params[":patient_id"] = $patient_id;
		return $this->fetchAll($sql, $params);
	}


} // END class DietInfo extends Dietary 