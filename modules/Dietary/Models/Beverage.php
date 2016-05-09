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


    $sql = "SELECT count(bev.id) as num, bev.name, pb.meal FROM {$this->tableName()} bev INNER JOIN {$patient_bev->tableName()} pb ON pb.beverage_id = bev.id INNER JOIN {$patient_info->tableName()} pi ON pi.patient_id = pb.patient_id INNER JOIN {$schedule->tableName()} sch ON sch.patient_id = pb.patient_id WHERE pi.location_id = :location_id AND sch.datetime_admit <= :date AND (sch.datetime_discharge >= :date OR sch.datetime_discharge IS NULL) AND sch.status = 'Approved' GROUP BY bev.id";

    $params[":location_id"] = $location->id;
    $params[":date"] = $date;

    return $this->fetchAll($sql, $params);

  }


} // END class Order extends Dietary