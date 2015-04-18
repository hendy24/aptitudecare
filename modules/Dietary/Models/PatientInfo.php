<?php

class PatientInfo extends Dietary {

	protected $table = 'patient_info';


	public function fetchDietInfo($patientid) {
		$sql = "SELECT pi.* FROM {$this->tableName()} pi WHERE pi.patient_id = :patientid LIMIT 1";
		$params[":patientid"] = $patientid;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this;
		}
	}

}
