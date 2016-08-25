<?php

class PatientBeverage extends Dietary {

  protected $table = "patient_beverage";

  public function fetchPatientBeverage($patient_id, $meal) {
    $beverage = $this->loadTable("Beverage");
    $sql = "SELECT * FROM {$this->tableName()} po inner JOIN {$beverage->tableName()} AS d ON d.id = po.beverage_id and po.patient_id = :patient_id AND meal = :meal";
    $params[":patient_id"] = $patient_id;
    $params[":meal"] = $meal;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchBeverageByPatient($patient_id) {
    $beverage = $this->loadTable("Beverage");
    $sql = "SELECT GROUP_CONCAT(b.name separator ', ') AS list, po.meal FROM {$this->tableName()} po inner JOIN {$beverage->tableName()} AS b ON b.id = po.beverage_id and po.patient_id = :patient_id GROUP BY po.id";
    $params[":patient_id"] = $patient_id;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }


  public function fetchByPatientAndBeverageId($patient_id, $beverage_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND beverage_id = :beverage_id";
    $params = array(":patient_id" => $patient_id, ":beverage_id" => $beverage_id);
    $result = $this->fetchOne($sql, $params);
    //pr($result); exit;

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }


    public function deleteBeverage($patient_id, $bev_name, $meal) {
        $bev = $this->loadTable("Beverage");
        $sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id AND beverage_id = (SELECT id FROM {$bev->tableName()} WHERE name = :bev_name)  AND meal = :meal";
        $params = array(
            ":patient_id" => $patient_id,
            ":bev_name" => $bev_name,
            ":meal" => $meal
        );

        if ($this->deleteQuery($sql, $params)) {
            return true;
        }

        return false;
    }


    public function deletePatientBevs($patient_id) {
        $sql = "DELETE FROM {$this->tableName()} WHERE patient_id = :patient_id";
        $params = array(
            ":patient_id" => $patient_id,
        );
        if ($this->deleteQuery($sql, $params)) {
            return true;
        }

        return false;
    }

}