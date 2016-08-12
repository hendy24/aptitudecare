<?php

class Snack extends Dietary {

	protected $table = "snack";


	public function fetchSnackReport($location_id) {
		
		$patient = $this->loadTable('Patient');
		$schedule = $this->loadTable('Schedule');
		$room = $this->loadTable('Room');
		$patient_snack = $this->loadTable('PatientSnack');
		$diet_order = $this->loadTable('DietOrder');
		$patient_diet_order = $this->loadTable('PatientDietOrder');
		$allergy = $this->loadTable('Allergy');
		$patient_allergy = $this->loadTable('PatientFoodInfo');
    	$sql = "SELECT 
    			room.number,
    			CONCAT(patient.last_name, ', ', patient.first_name) AS patient_name,
    			diet_order.name AS diet,
    			allergy.name AS allergy,
    			snack.name AS snack,
    			patient_snack.time
    		FROM {$this->tableName()} AS snack
    		INNER JOIN {$patient_snack->tableName()} patient_snack
    			ON patient_snack.snack_id = snack.id
    		INNER JOIN {$patient->tableName()} patient 
    			ON patient.id = patient_snack.patient_id
 			INNER JOIN {$schedule->tableName()} sch
 				ON sch.patient_id = patient.id
    		INNER JOIN {$room->tableName()} room 
    			ON room.id = sch.room_id
    		INNER JOIN {$patient_diet_order->tableName()} patient_diet_order
    			ON patient_diet_order.patient_id = patient.id
    		INNER JOIN {$diet_order->tableName()} diet_order
    			ON diet_order.id = patient_diet_order.diet_order_id
    		LEFT JOIN {$patient_allergy->tableName()} patient_allergy
    			ON patient_allergy.patient_id = patient.id
    		LEFT JOIN {$allergy->tableName()} allergy	
    			ON allergy.id = patient_allergy.food_id
    		WHERE 
    			sch.location_id = :location_id
                AND sch.status='Approved'
    		GROUP BY patient.id, snack.id
    		ORDER BY room.number, patient_snack.time";

    	$params[":location_id"] = $location_id;
    	return $this->fetchAll($sql, $params);
  	}



}