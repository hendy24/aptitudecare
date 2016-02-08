<?php

class PatientDietInfo extends Dietary {

  protected $table = "patient_diet_info";


  public function fetchPatientDietInfo($patient_id) {
    $diet_info = $this->loadTable("DietInfo");
    $sql = "SELECT * FROM {$this->tableName()} pdi right JOIN {$diet_info->tableName()} AS d ON d.id = pdi.diet_info_id and pdi.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndDietInfoId($patient_id, $diet_info_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND diet_info_id = :diet_info_id";
    $params = array(":patient_id" => $patient_id, ":diet_info_id" => $diet_info_id);
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }

}