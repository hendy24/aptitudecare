<?php

class PatientSupplement extends Dietary {

  protected $table = "patient_supplement";

  public function fetchPatientSupplement($patient_id) {
    //$supplement = $this->loadTable("Supplement");

    $sql = "SELECT * FROM {$this->tableName()} po inner JOIN dietary_supplement AS d ON d.id = po.supplement_id and po.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndSupplementId($patient_id, $supplement_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND supplement_id = :supplement_id";
    $params = array(":patient_id" => $patient_id, ":supplement_id" => $supplement_id);
    $result = $this->fetchOne($sql, $params);
    //pr($result); exit;

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }



  public function deleteSupplement($patient_id, $supplement_name) {
        $supplement = $this->loadTable("Supplement");
        $sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id AND supplement_id = (SELECT id FROM {$supplement->tableName()} WHERE name = :supplement_name)";
        $params = array(
            ":patient_id" => $patient_id,
            ":supplement_name" => $supplement_name
        );

        if ($this->deleteQuery($sql, $params)) {
            return true;
        }

        return false;

  }

}