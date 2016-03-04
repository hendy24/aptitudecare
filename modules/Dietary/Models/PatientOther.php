<?php

class PatientOther extends Dietary {

  protected $table = "patient_other";

  public function fetchPatientOther($patient_id) {
    $other = $this->loadTable("Other");
    $sql = "SELECT * FROM {$this->tableName()} po INNER JOIN {$other->tableName()} AS d ON d.id = po.other_id WHERE po.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }


  public function fetchOrderByPatient($patient_id) {
    $other = $this->loadTable("Other");
    $sql = "SELECT GROUP_CONCAT(d.name separator ', ') AS list FROM {$this->tableName()} po right JOIN {$other->tableName()} AS d ON d.id = po.other_id WHERE po.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    return $this->fetchOne($sql, $params);
  }


  public function fetchByPatientAndOrderId($patient_id, $order_id) {
    $sql = "SELECT * FROM {$this->tableName()} WHERE patient_id = :patient_id AND order_id = :order_id";
    $params = array(":patient_id" => $patient_id, ":order_id" => $order_id);
    $result = $this->fetchOne($sql, $params);
    //pr($result); exit;

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }
}