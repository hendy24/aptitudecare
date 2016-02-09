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

		return false;

	}


	public function deleteFoodInfoItem($patient_id, $name, $type = false) {
		if ($type == "allergy") {
			$params[":allergy"] = 1;
			$joinedTable = $this->loadTable("Allergy");
		} elseif ($type == "dislike") {
			$params[":allergy"] = 0;
			$joinedTable = $this->loadTable("Dislike");
		}

		$sql = "DELETE FROM {$this->tableName()} WHERE {$this->tableName()}.patient_id = :patient_id AND {$this->tableName()}.allergy = :allergy AND (SELECT id FROM {$joinedTable->tableName()} WHERE name = :name) = {$this->tableName()}.food_id";
		$params[":patient_id"] = $patient_id;
		$params[":name"] = $name;

		if ($this->deleteQuery($sql, $params)) {
			return true;
		}

		return false;
	}

}