<?php

class PatientFoodInfo extends Dietary {

	protected $table = "patient_food_info";


	public function fetchPatientAllergies($patient_id) {
		$allergy = $this->loadTable("Allergy");
		$sql = "SELECT a.* FROM {$this->tableName()} pfi INNER JOIN {$allergy->tableName()} AS a ON a.id = pfi.food_id WHERE patient_id = :patient_id AND allergy = 1";
		$params[":patient_id"] = $patient_id;
		$result = $this->fetchAll($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return array();
	}


	public function fetchAllergiesByPatient($patient_id) {
		$allergy = $this->loadTable("Allergy");
		$sql = "SELECT GROUP_CONCAT(a.name separator ', ') AS list FROM {$this->tableName()} pfi INNER JOIN {$allergy->tableName()} AS a ON a.id = pfi.food_id WHERE patient_id = :patient_id AND allergy = 1";
		$params[":patient_id"] = $patient_id;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return false;
	}

	public function fetchPatientDislikes($patient_id) {
		$dislike = $this->loadTable("Dislike");
		$sql = "SELECT d.* FROM {$this->tableName()} pfi INNER JOIN {$dislike->tableName()} AS d ON d.id = pfi.food_id WHERE patient_id = :patient_id AND allergy = 0";
		$params[":patient_id"] = $patient_id;
		$result = $this->fetchAll($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return array();

	}



	public function fetchDislikesByPatient($patient_id) {
		$dislike = $this->loadTable("Dislike");
		$sql = "SELECT GROUP_CONCAT(d.name separator ', ') AS list FROM {$this->tableName()} pfi INNER JOIN {$dislike->tableName()} AS d ON d.id = pfi.food_id WHERE patient_id = :patient_id AND allergy = 0";
		$params[":patient_id"] = $patient_id;
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		}

		return false;

	}


	public function deleteFoodInfoItems($patient_id, $type = false) {
		if ($type == "allergy") {
			$params[":allergy"] = 1;
		} elseif ($type == "dislike") {
			$params[":allergy"] = 0;
		}

		$sql = "DELETE FROM {$this->tableName()} WHERE {$this->tableName()}.patient_id = :patient_id AND {$this->tableName()}.allergy = :allergy";
		$params[":patient_id"] = $patient_id;

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}

}