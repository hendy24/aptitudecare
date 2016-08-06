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

    $sql = "SELECT 
              p.id, 
              p.public_id,
              r.number AS room, 
              CONCAT(p.last_name, ', ', p.first_name) AS patient_name,
              (SELECT GROUP_CONCAT(do.name separator ', ') as diet_order FROM {$diet_order->tableName()} do INNER JOIN {$this->tableName()} pdo ON pdo.diet_order_id = do.id WHERE pdo.patient_id = p.id GROUP BY pdo.patient_id) AS diet_order,
              (SELECT GROUP_CONCAT(t.name separator ', ') as texture FROM {$texture->tableName()} t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE is_liquid = 0 AND pt.patient_id = p.id GROUP BY pt.patient_id) AS texture,
              (SELECT GROUP_CONCAT(t.name separator ', ') as texture FROM {$texture->tableName()} t INNER JOIN {$patient_texture->tableName()} pt ON pt.texture_id = t.id WHERE is_liquid = 1 AND pt.patient_id = p.id GROUP BY pt.patient_id) AS liquid_consistency
            FROM {$this->tableName()} pdo 
              INNER JOIN {$patient->tableName()} p ON p.id = pdo.patient_id 
              INNER JOIN {$schedule->tableName()} s ON s.patient_id = p.id 
              INNER JOIN {$room->tableName()} r ON r.id = s.room_id 
              INNER JOIN {$patient_info->tableName()} pi ON pi.patient_id = p.id
              LEFT JOIN {$diet_order->tableName()} do ON do.id = pdo.diet_order_id
              LEFT JOIN {$patient_texture->tableName()} pt ON p.id 
              LEFT JOIN {$texture->tableName()} t ON t.id = pt.texture_id 
            WHERE pi.location_id = :location_id 
              AND s.status = 'Approved'
            GROUP BY p.id ORDER BY {$order_by} ASC";


    $params[":location_id"] = $location_id;
    return $this->fetchAll($sql, $params);
  }

}