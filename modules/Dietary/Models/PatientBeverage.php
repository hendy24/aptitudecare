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




  public function fetchBeverageReport($location, $date){
    $patient = $this->loadTable('Patient');
    $schedule = $this->loadTable('Schedule');
    $bev = $this->loadTable('Beverage');
    $room = $this->loadTable('Room');


    $sql = "SELECT count(bev.id) AS quantity,
              bev.name
            FROM {$this->tableName()} AS pb
            INNER JOIN {$patient->tableName()} p ON p.id = pb.patient_id
            INNER JOIN {$schedule->tableName()} s
            INNER JOIN {$room->tableName()} r ON r.id = s.room_id     
            LEFT JOIN {$bev->tableName()} bev ON bev.id = pb.beverage_id
            WHERE s.status = 'Approved'
            AND s.location_id = :location_id
            AND (s.datetime_discharge >= :current_date OR s.datetime_discharge IS NULL)
            GROUP BY bev.id
            ORDER BY r.number ASC";

    $params[":location_id"] = $location->id;
    $params[":current_date"] = $date;

    return $this->fetchAll($sql, $params);

    // $sql = <<<EOD
    // SELECt count(f.id) quantity, f.name
    // FROM ac_patient AS p
    // INNER JOIN admit_schedule s ON s.patient_id = p.id
    // INNER JOIN dietary_patient_beverage e ON e.patient_id = p.id
    // left join dietary_beverage f on e.beverage_id = f.id
    // left join admit_room g on s.room_id = g.id
    // WHERE s.status='Approved'
    // AND s.location_id = {$location->id}
    // AND ((s.datetime_discharge >= '{$date}' OR s.datetime_discharge IS NULL) and s.datetime_admit <= '{$date}')
    // group by f.name
    // ORDER BY s.room_id ASC
    // EOD;
    // $result = $this->fetchAll($sql);

    // if (!empty ($result)) {
    // return $result;
    // } else {
    // return $this->fetchColumnNames();
    // }

  }

}