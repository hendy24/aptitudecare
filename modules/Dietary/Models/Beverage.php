<?php

/**
 * undocumented class
 *
 * @package default
 * @author
 **/
class Beverage extends Dietary {

  protected $table = "beverage";


  public function fetchBeverageReport($location, $date){
    $patient_info = $this->loadTable('PatientInfo');
    $schedule = $this->loadTable('Schedule');
    $patient_bev = $this->loadTable('PatientBeverage');


    $sql = "SELECT count(bev.id) AS num, bev.name, pb.meal FROM dietary_beverage bev INNER JOIN dietary_patient_beverage pb ON pb.beverage_id = bev.id INNER JOIN admit_schedule sch ON sch.patient_id = pb.patient_id WHERE sch.location_id = :location_id AND ((sch.status = 'Approved' AND (sch.datetime_discharge IS NULL OR sch.datetime_discharge >= :date)) OR (sch.status = 'Discharged' AND sch.datetime_discharge >= :date)) GROUP BY pb.meal, bev.id ORDER BY pb.meal";

    $params[":location_id"] = $location->id;
    $params[":date"] = $date;
    return $this->fetchAll($sql, $params);

  }


} // END class Order extends Dietary
