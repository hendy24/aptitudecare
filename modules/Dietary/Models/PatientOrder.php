<?php

class PatientOrder extends Dietary {

  protected $table = "patient_order";

  public function fetchPatientOrder($patient_id) {
    $order = $this->loadTable("Order");
    $sql = "SELECT * FROM {$this->tableName()} po right JOIN {$order->tableName()} AS d ON d.id = po.order_id and po.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }


  public function fetchOrderByPatient($patient_id) {
    $order = $this->loadTable("Order");
    $sql = "SELECT GROUP_CONCAT(d.name separator ', ') AS list FROM {$this->tableName()} po right JOIN {$order->tableName()} AS d ON d.id = po.order_id WHERE po.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    return $this->fetchOne($sql, $params);
  }


  public function fetchByPatientAndOrderId($patient_id, $order_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND order_id = :order_id";
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