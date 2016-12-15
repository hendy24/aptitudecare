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
        $texture = $this->loadTable('Texture');
        $patient_texture = $this->loadTable('PatientTexture');

    	$sql = "SELECT room.number, 
    CONCAT(patient.last_name, ', ', patient.first_name) AS patient_name, 
    (SELECT GROUP_CONCAT(diet_order.name, ', ') FROM dietary_patient_diet_order AS patient_diet_order INNER JOIN dietary_diet_order diet_order ON diet_order.id = patient_diet_order.diet_order_id WHERE patient_diet_order.patient_id = patient.id GROUP BY patient_diet_order.patient_id) AS diet,
    snack.name,
    (SELECT GROUP_CONCAT(allergy.name, ' ') FROM dietary_patient_food_info AS patient_allergy INNER JOIN dietary_allergy allergy ON allergy.id=patient_allergy.food_id WHERE patient_allergy.patient_id=patient.id AND patient_allergy.allergy = 1) AS allergy, 
    patient_snack.time,
    (SELECT GROUP_CONCAT(t.name, ' ') FROM {$patient_texture->tableName()} AS pt INNER JOIN {$texture->tableName()} t ON t.id = pt.texture_id WHERE pt.patient_id = patient.id) AS texture
FROM dietary_snack AS snack 
    INNER JOIN dietary_patient_snack patient_snack ON patient_snack.snack_id = snack.id 
    INNER JOIN ac_patient patient ON patient.id = patient_snack.patient_id INNER JOIN admit_schedule sch ON sch.patient_id = patient.id 
    INNER JOIN admit_room room ON room.id = sch.room_id 
WHERE sch.location_id = :location_id AND sch.status='Approved' ORDER BY room.number, patient_snack.time";

    	$params[":location_id"] = $location_id;
    	return $this->fetchAll($sql, $params);
  	}



}