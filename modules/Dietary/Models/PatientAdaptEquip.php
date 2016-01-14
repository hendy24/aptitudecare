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

  public function fetchByLocation($location) {
    $adapt_equip = $this->loadTable("AdaptEquip");
    $schedule = $this->loadTable("Schedule");
    $room = $this->loadTable("Room");
    $sql =
<<<EOD

    Select p.last_name, p.first_name, p.id patient_id, r.number, f.id adapt_id, f.name
    FROM ac_patient AS p
    INNER JOIN admit_schedule s ON s.patient_id = p.id
    INNER JOIN admit_room r ON r.id = s.room_id
    LEFT JOIN dietary_patient_adapt_equip e ON e.patient_id = p.id
    left join dietary_adapt_equip f on e.adapt_equip_id = f.id
    WHERE s.status='Approved' AND s.location_id = {$location->id}
EOD;

    $compiled_patients  = array();
    $duped_patients = array();


    //$params[":location_id"] = 3;
    $patients = $this->fetchAll($sql);


    foreach ($patients as $key => $value) {
      if(array_search($value, $compiled_patients)){
        //$value->name = array()
        array_push($duped_patients, $value, true);
      }
      else{
        array_push($compiled_patients, $value, true);
      }
    }
    pr($duped_patients); exit;

    if (!empty ($compiled_patients)) {
      return $compiled_patients;
    } else {
      return $this->fetchColumnNames();
    }
  }
}