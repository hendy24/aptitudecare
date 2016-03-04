<?php

class Dietary extends AppModel {

	protected $prefix = "dietary";

	public function fetchByName($name = false, $save_other = false) {
		if ($name) {
			$sql = "SELECT * FROM {$this->tableName()} WHERE name = :name LIMIT 1";
			$params[":name"] = $name;
			$result = $this->fetchOne($sql, $params);

			if (empty ($result)) {
				$this->name = $name;
				if ($save_other) {
					$this->is_other = true;
				}
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



/*
 * ---------------------------------------------------------------------------
 *  Remove "other" items from db tables for patients 
 * ---------------------------------------------------------------------------
 */
	public function removeOtherItems($patient_id, $table_name = false) {
      $other_table = $this->loadTable($table_name);
      $sql = "SELECT t1.id AS t1_id, t2.id AS t2_id FROM {$this->tableName()} t1 INNER JOIN {$other_table->tableName()} t2 ON t2.id = t1.{$other_table->joinName()} WHERE t1.patient_id = :patient_id AND t2.is_other = 1";

      $params[":patient_id"] = $patient_id;

      $result = $this->fetchOne($sql, $params);

      if (!empty ($result)) {
        // delete from the data table
        $sql = "DELETE t1, t2 FROM {$this->tableName()} t1 JOIN {$other_table->tableName()} t2 ON t2.id = t1.{$other_table->joinName()} WHERE t1.id = :t1_id";
        $params2[":t1_id"] = $result->t1_id;
        if ($this->deleteQuery($sql, $params2)) {
        	return true;
        }
      }

      return false;
  }




}