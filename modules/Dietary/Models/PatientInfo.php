<?php

class PatientInfo extends Dietary {

	protected $table = 'patient_info';


	public function fetchDietInfo($patientid) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE {$this->tableName()}.patient_id = :patientid LIMIT 1";
		$params[":patientid"] = $patientid;
		return $this->fetchOne($sql, $params);
	}

}