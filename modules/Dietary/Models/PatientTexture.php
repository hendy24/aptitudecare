<?php

class PatientTexture extends Dietary {

  protected $table = "patient_texture";

  public function fetchPatientTexture($patient_id) {
    $texture = $this->loadTable("Texture");
    $sql = "SELECT * FROM {$this->tableName()} pt RIGHT JOIN {$texture->tableName()} AS d ON d.id = pt.texture_id and pt.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }


  public function fetchTexturesByPatient($patient_id) {
    $texture = $this->loadTable("Texture");
    $sql = "SELECT GROUP_CONCAT(d.name separator ', ') AS names FROM {$this->tableName()} pt RIGHT JOIN {$texture->tableName()} AS d ON d.id = pt.texture_id WHERE pt.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndTextureId($patient_id, $texture_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND texture_id = :texture_id";
    $params = array(":patient_id" => $patient_id, ":texture_id" => $texture_id);
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }

}