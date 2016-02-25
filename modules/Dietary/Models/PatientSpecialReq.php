<?php

class PatientSpecialReq extends Dietary {

  protected $table = "patient_special_req";

  
  public function fetchPatientSpecialReq($patient_id, $meal) {
    $special_req = $this->loadTable("SpecialReq");
    $sql = "SELECT * FROM {$this->tableName()} po inner JOIN {$special_req->tableName()} AS d ON d.id = po.special_req_id and po.patient_id = :patient_id AND meal = :meal";
    $params[":patient_id"] = $patient_id;
    $params[":meal"] = $meal;
    $result = $this->fetchAll($sql, $params);

    if (!empty ($result)) {
      return $result;
    }

    return false;

  }

  public function fetchByPatientAndSpecialReqId($patient_id, $special_req_id) {
    $sql = "SELECT * FROM {$this->tableName()} where patient_id = :patient_id AND special_req_id = :special_req_id";
    $params = array(":patient_id" => $patient_id, ":special_req_id" => $special_req_id);
    $result = $this->fetchOne($sql, $params);
    //pr($result); exit;

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }
  }

  public function fetchSpecialReqReport($location, $date){
    $sql = <<<EOD
      SELECt count(f.id) quantity, f.name
      FROM ac_patient AS p
      INNER JOIN admit_schedule s ON s.patient_id = p.id
      INNER JOIN dietary_patient_beverage e ON e.patient_id = p.id
      left join dietary_beverage f on e.beverage_id = f.id
      left join admit_room g on s.room_id = g.id
      WHERE s.status='Approved'
      AND s.location_id = {$location->id}
      AND ((s.datetime_discharge >= '{$date}' OR s.datetime_discharge IS NULL) and s.datetime_admit <= '{$date}')
      group by f.name
      ORDER BY s.room_id ASC
EOD;
    $result = $this->fetchAll($sql);

    if (!empty ($result)) {
      return $result;
    } else {
      return $this->fetchColumnNames();
    }

  }

}