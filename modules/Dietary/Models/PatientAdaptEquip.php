<?php

class PatientAdaptEquip extends Dietary {

  protected $table = "patient_adapt_equip";

  public function fetchPatientAdaptEquip($patient_id) {
    $adapt_equip = $this->loadTable("AdaptEquip");
    $sql = "SELECT * FROM {$this->tableName()} pae inner JOIN {$adapt_equip->tableName()}  AS d ON d.id = pae.adapt_equip_id and pae.patient_id = :patient_id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndAdaptEquipId($patient_id, $adapt_equip_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND adapt_equip_id = :adapt_equip_id";
    $params = array(":patient_id" => $patient_id, ":adapt_equip_id" => $adapt_equip_id);
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }


    public function deleteAdaptEquip($patient_id, $adapt_equip_name) {
        $adapt_equip = $this->loadTable("AdaptEquip");
        $sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id AND adapt_equip_id = (SELECT id FROM {$adapt_equip->tableName()} WHERE name = :adapt_equip_name)";
        $params = array(
            ":patient_id" => $patient_id,
            ":adapt_equip_name" => $adapt_equip_name
        );

        if ($this->deleteQuery($sql, $params)) {
            return true;
        }

        return false;
    }


}