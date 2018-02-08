<?php

class SnackReport extends Dietary {

  protected $table = "snack_report";

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
//    $adapt_equip = $this->loadTable("AdaptEquip");
//    $schedule = $this->loadTable("Schedule");
//    $room = $this->loadTable("Room");

    $sql =

<<<EOD
    SELECT g.number, p.id patient_id, s.id admit_schedule_id, p.last_name, p.first_name, s.location_id, Group_Concat(Distinct f.name SEPARATOR ', ') name, e.time
    FROM ac_dev.ac_patient AS p
    INNER JOIN ac_dev.admit_schedule s ON s.patient_id = p.id
    inner join ac_dev.dietary_patient_snack e ON e.patient_id = p.id
    left join ac_dev.dietary_snack f on e.snack_id = f.id
    left join ac_dev.admit_room g on s.room_id = g.id
    WHERE s.status='Approved'
    AND s.location_id = {$location->id}
    AND (s.datetime_discharge >= now() OR s.datetime_discharge IS NULL)
    group by g.number, p.id, s.id, p.last_name, p.first_name, s.location_id, /*f.id, f.name,*/ e.time
    ORDER BY s.room_id ASC
EOD;

    $compiled_patients  = array();
    $duped_patients = array();


    //$params[":location_id"] = 3;
    $patients = $this->fetchAll($sql);


    foreach ($patients as $key => $value) {
      $compiled_patients[$value->patient_id][] = $value;
    }


    if (!empty ($compiled_patients)) {
      return $compiled_patients;
    } else {
      return $this->fetchColumnNames();
    }
  }


}