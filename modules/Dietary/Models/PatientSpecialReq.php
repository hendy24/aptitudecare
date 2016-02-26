<?php

class PatientSpecialReq extends Dietary {

	protected $table = "patient_special_req";


	public function fetchSpecialRequestsByPatient($patient_id) {
		$special_req = $this->loadTable('SpecialReq');
		$sql = "SELECT GROUP_CONCAT(sr.name separator ', ') AS list, psr.meal FROM {$this->tableName()} psr INNER JOIN {$special_req->tableName()} sr ON sr.id = psr.special_req_id WHERE psr.patient_id = :patient_id GROUP BY sr.id";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);

	}

}