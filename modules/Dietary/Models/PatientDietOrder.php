<?php

class PatientDietOrder extends Dietary {

  protected $table = "patient_diet_order";


  public function fetchPatientDietOrder($patient_id) {
    $diet_order = $this->loadTable("DietOrder");
    $sql = "SELECT * FROM {$this->tableName()} pdi INNER JOIN {$diet_order->tableName()} AS d ON d.id = pdi.diet_order_id WHERE pdi.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);
    
    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchDietInfoByPatient($patient_id) {
    $diet_info = $this->loadTable("DietInfo");
    $sql = "SELECT GROUP_CONCAT(d.name separator ', ') AS list FROM {$this->tableName()} pdi right JOIN {$diet_info->tableName()} AS d ON d.id = pdi.diet_order_id WHERE pdi.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndDietInfoId($patient_id, $diet_order_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND diet_order_id = :diet_order_id";
    $params = array(":patient_id" => $patient_id, ":diet_order_id" => $diet_order_id);
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }


  public function removeOtherItems($patient_id) {
      $diet_order = $this->loadTable('DietOrder');
      $sql = "DELETE FROM {$this->tableName()} pdo INNER JOIN {$diet_order->tableName()} do ON do.id = pdo.diet_order_id WHERE pdo.patient_id = :patient_id";

      $params[":patient_id"] = $patient_id;

      if ($this->deleteQuery($sql, $params)) {
        return true;
      }

      return false;
  }

}