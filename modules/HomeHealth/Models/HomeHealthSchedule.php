<?php

class HomeHealthSchedule extends AppModel {

	public $table = 'home_health_schedule';
	public $belongsTo = array(
		'patient' => array(
			'inner_key' => 'patient_id',
			'foreign_key' => 'id'
		)
	);


	/*
	 * -------------------------------------------------------------------------
	 *  FETCH ADMISSIONS FOR THE WEEK
	 * -------------------------------------------------------------------------
	 */	

	public function fetchAdmits($datetime_start, $datetime_end, $location, $orderby = null) {


		$sql = "SELECT `{$this->table}`.*, `patient`.*, `healthcare_facility`.`name` AS location_name FROM {$this->table} INNER JOIN `patient` ON {$this->table}.`patient_id` = `patient`.`id` INNER JOIN `healthcare_facility` ON `healthcare_facility`.`id` = `{$this->table}`.`location_id` WHERE {$this->table}.`datetime_admit` >= :datetime_start AND {$this->table}.`datetime_admit` <= :datetime_end AND {$this->table}.`location_id` = :location_id AND ({$this->table}.`status` = 'Approved' OR {$this->table}.`status` = 'Pending')";

		if ($orderby != null) {
			$sql .= " ORDER BY :orderby";
			$params[':orderby'] = $orderby;
		}

		$params = array(
			':datetime_start' => date('Y-m-d 00:00:01', strtotime($datetime_start)),
			':datetime_end' => date('Y-m-d 23:59:59', strtotime($datetime_end)),
			':location_id' => $location,
		);

		return $this->fetchAll($sql, $params);
	}



	/*
	 * -------------------------------------------------------------------------
	 *  FETCH DISCHARGES FOR THE WEEK
	 * -------------------------------------------------------------------------
	 */

	public function fetchDischarges($datetime_start, $datetime_end, $location, $status = null, $orderby = null) {

		if ($status == null) {
			$status = 'Scheduled Discharge';
		}

		if ($orderby == null) {
			$orderby = 'datetime_discharge ASC';
		}

		$sql = "SELECT * FROM {$this->table} INNER JOIN `patient` ON {$this->table}.`patient_id` = `patient`.`id` WHERE {$this->table}.`datetime_discharge` >= :datetime_start AND {$this->table}.`datetime_discharge` <= :datetime_end AND {$this->table}.`location_id` = :location_id AND {$this->table}.`status` = :status ORDER BY :orderby";
		$params = array(
			':datetime_start' => $datetime_start,
			':datetime_end' => $datetime_end,
			':location_id' => $location,
			':status' => $status,
			':orderby' => $orderby
		);

		return $this->fetchAll($sql, $params);

	}


}