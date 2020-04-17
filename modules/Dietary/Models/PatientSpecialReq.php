<?php

class PatientSpecialReq extends Dietary {

	protected $table = "patient_special_req";


	// public function fetchSpecialRequestsByPatient($patient_id) {
	// 	$special_req = $this->loadTable('SpecialReq');
	// 	$sql = "SELECT GROUP_CONCAT(sr.name separator ', ') AS list, psr.meal FROM {$this->tableName()} psr INNER JOIN {$special_req->tableName()} sr ON sr.id = psr.special_req_id WHERE psr.patient_id = :patient_id GROUP BY sr.id";
	// 	$params[":patient_id"] = $patient_id;
	// 	return $this->fetchOne($sql, $params);

	// }


	public function fetchSpecialRequestsByPatient($patient_id, $meal) {
		$spec_req = $this->loadTable("SpecialReq");
		$sql = "SELECT * FROM {$this->tableName()} po INNER JOIN {$spec_req->tableName()} AS d ON d.id = po.special_req_id AND po.patient_id = :patient_id AND meal = :meal";
		$params = array(
			":patient_id" => $patient_id,
			":meal" => $meal
		);
		$result = $this->fetchAll($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return array();
	}


	public function fetchByPatientAndSpecialReqId($patient_id, $special_req_id) {
		$sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id AND special_req_id = :special_req_id";
		$params = array(":patient_id" => $patient_id, ":special_req_id" => $special_req_id);

		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this->fetchColumnNames();
		}
	}

	public function deleteSpecialReq($patient_id, $spec_req_id, $meal) {
		$special_req = $this->loadTable("SpecialReq");
		$sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id AND special_req_id = :spec_req_id AND meal = :meal";
		$params = array(
			":patient_id" => $patient_id,
			":spec_req_id" => $spec_req_id,
			":meal" => $meal
		);

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}


	public function deleteSpecialReqs($patient_id, $spec_req_name, $meal) {
		$sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id";
		$params = array(
			":patient_id" => $patient_id,
		);

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}


}