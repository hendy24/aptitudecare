<?php

class Snack extends Dietary {

	protected $table = "snack";


	public function fetchSnackReport($location_id, $date) {
		$patient_snack = $this->loadTable('PatientSnack');
    	$sql = "SELECT count(snack.id) AS num, snack.name, ps.time FROM {$this->tableName()} snack INNER JOIN {$patient_snack->tableName()} ps ON ps.snack_id = snack.id INNER JOIN admit_schedule sch ON sch.patient_id = ps.patient_id WHERE sch.location_id = :location_id AND sch.datetime_admit <= :date AND (sch.datetime_discharge >= :date OR sch.datetime_discharge IS NULL) AND sch.status = 'Approved' GROUP BY ps.time, snack.id ORDER BY ps.time";
    	$params[":location_id"] = $location_id;
    	$params[":date"] = $date;

    	return $this->fetchAll($sql, $params);
  	}



}