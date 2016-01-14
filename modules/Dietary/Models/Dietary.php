<?php

class Dietary extends AppModel {

	protected $prefix = "dietary";

	public function fetchByName($name = false) {
		if ($name) {
			$sql = "SELECT * FROM {$this->tableName()} WHERE name = :name LIMIT 1";
			$params[":name"] = $name;
			$result = $this->fetchOne($sql, $params);

			if (empty ($result)) {
				$this->name = $name;
				$this->save();
				return $this;
			} else {
				return $result;
			}
		}

		return false;
	}

	public function fetchByPatientAndFoodId($patient_id, $food_id) {
		$sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND food_id = :food_id";
		$params = array(":patient_id" => $patient_id, ":food_id" => $food_id);
		$result = $this->fetchOne($sql, $params);

		if (!empty ($result)) {
			return $result;
		} else {
			return $this->fetchColumnNames();
		}
	}

}