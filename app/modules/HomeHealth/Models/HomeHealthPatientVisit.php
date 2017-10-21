<?php

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class HomeHealthPatientVisit extends HomeHealth {
	protected $table = "patient_visit";


	public function fetchPatientVisits($patient_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id";
		$params[":patient_id"] = $patient_id;
		return $this->fetchAll($sql, $params);
	}




} // END classHomeHealthPatientVisit extends HomeHealth 