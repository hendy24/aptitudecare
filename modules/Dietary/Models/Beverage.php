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

/*
    $sql = "SELECT count(bev.id) AS num, bev.name, pb.meal 
			FROM dietary_beverage bev 		
			INNER JOIN dietary_patient_beverage pb ON pb.beverage_id = bev.id 
			INNER JOIN dietary_patient_info as dpi on dpi.patient_id = pb.patient_id
			INNER JOIN admit_schedule sch ON sch.patient_id = pb.patient_id 
			WHERE sch.location_id = :location_id AND ((sch.status = 'Approved' AND (sch.datetime_discharge IS NULL OR sch.datetime_discharge >= :date)) OR (sch.status = 'Discharged' AND sch.datetime_discharge >= :date)) GROUP BY pb.meal, bev.id ORDER BY pb.meal";
	*/
	/*
	$sql = "SELECT count(bev.id) AS num, bev.name, pb.meal 
			FROM dietary_beverage bev 		
			INNER JOIN dietary_patient_beverage pb ON pb.beverage_id = bev.id 
            WHERE patient_id IN (
				SELECT dpi.patient_id FROM dietary_patient_info as dpi
				INNER JOIN admit_schedule sch ON sch.patient_id = dpi.patient_id 
				WHERE dpi.location_id = :location_id AND ((sch.status = 'Approved' AND (sch.datetime_discharge IS NULL OR sch.datetime_discharge >= :date)) OR (sch.status = 'Discharged' AND sch.datetime_discharge >= :date)))
			GROUP BY pb.meal, bev.id 
			ORDER BY pb.meal, bev.name";
	*/
	$sql = "SELECT count(bev.id) AS num, bev.name, pb.meal, vdpo.other_id, liq_name
			FROM dietary_beverage bev
			INNER JOIN dietary_patient_beverage pb ON pb.beverage_id = bev.id 
            LEFT JOIN (SELECT patient_id, other_id FROM dietary_patient_other dpo WHERE other_id = 1) as vdpo ON vdpo.patient_id = pb.patient_id
            LEFT JOIN (SELECT name as liq_name, patient_id FROM dietary_texture,dietary_patient_texture WHERE dietary_texture.id = dietary_patient_texture.texture_id AND is_liquid = 1 ) vlo ON vlo.patient_id = pb.patient_id
            WHERE pb.patient_id IN (
				SELECT dpi.patient_id FROM dietary_patient_info as dpi
				INNER JOIN admit_schedule sch ON sch.patient_id = dpi.patient_id 
				WHERE dpi.location_id = :location_id AND ((sch.status = 'Approved' AND (sch.datetime_discharge IS NULL OR sch.datetime_discharge >= :date)) OR (sch.status = 'Discharged' AND sch.datetime_discharge >= :date)))
			GROUP BY pb.meal, bev.id, other_id, liq_name
			ORDER BY pb.meal, bev.name";
	

    $params[":location_id"] = $location->id;
    $params[":date"] = $date;
    return $this->fetchAll($sql, $params);

  }


} // END class Order extends Dietary
