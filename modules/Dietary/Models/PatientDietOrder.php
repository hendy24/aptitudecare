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

  public function fetchByPatientAndDietOrderId($patient_id, $diet_order_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND diet_order_id = :diet_order_id";
    $params = array(":patient_id" => $patient_id, ":diet_order_id" => $diet_order_id);
    $result = $this->fetchOne($sql, $params);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }

  public function fetchPatientCensus($location_id, $order_by = false) {
    $patient = $this->loadTable('Patient');
    $patient_info = $this->loadTable('PatientInfo');
    $schedule = $this->loadTable('Schedule');
    $room = $this->loadTable('Room');
    $diet_order = $this->loadTable('DietOrder');
    $texture = $this->loadTable('Texture');
    $patient_texture = $this->loadTable('PatientTexture');
    $allergy = $this->loadTable('Allergy');
    $food_info = $this->loadTable('PatientFoodInfo');

    $sql = "SELECT 
              p.id, 
              p.public_id,
              r.number AS room, 
              CONCAT(p.last_name, ', ', p.first_name) AS patient_name,
              (SELECT GROUP_CONCAT(do.name separator ', ') as diet_order FROM {$diet_order->tableName()} do INNER JOIN {$this->tableName()} pdo ON pdo.diet_order_id = do.id WHERE pdo.patient_id = p.id GROUP BY pdo.patient_id) AS diet_order,
              (SELECT GROUP_CONCAT(t.name separator ', ') as texture FROM {$texture->tableName()} t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE t.is_liquid = 0 AND t.is_other = 0 AND pt.patient_id = p.id GROUP BY pt.patient_id) AS texture,
              (SELECT GROUP_CONCAT(t.name separator ', ') as texture FROM {$texture->tableName()} t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE pt.patient_id = p.id AND (is_liquid = 1 OR is_other = 1) GROUP BY pt.patient_id) AS liquid_fluid_order,
              (SELECT GROUP_CONCAT(a.name separator ', ') as patient_allergy FROM {$allergy->tableName()} a INNER JOIN {$food_info->tableName()} food_info ON a.id = food_info.food_id WHERE food_info.allergy = 1 AND food_info.patient_id = p.id GROUP BY food_info.patient_id) AS allergies
            FROM {$this->tableName()} pdo 
              INNER JOIN {$patient->tableName()} p ON p.id = pdo.patient_id 
              INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id 
              INNER JOIN {$room->tableName()} r ON r.id = s.room 
              INNER JOIN {$patient_info->tableName()} pi ON pi.patient_id = p.id
            WHERE s.location_id = :location_id 
              AND (s.status = 'Approved' AND (s.datetime_discharge IS NULL OR s.datetime_discharge >= :now) AND s.location_id = :location_id)
              OR (s.status = 'Discharged' AND s.datetime_discharge >= :now AND s.location_id = :location_id)
            GROUP BY p.id ORDER BY {$order_by} ASC";

    $params[":location_id"] = $location_id;
    $params[":now"] = mysql_date() . " 23:59:59";
    return $this->fetchAll($sql, $params);
  }

}