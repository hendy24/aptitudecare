<?php

class HomeHealthSchedule extends AppModel {

	protected $table = 'home_health_schedule';
	protected $belongsTo = array(
		'Patient' => array(
			'table' => 'patient',
			'join_type' => 'inner',
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


		$sql = "SELECT `{$this->table}`.*, {$this->table}.public_id AS hh_public_id, `patient`.*, `location`.`name` AS location_name, `healthcare_facility`.`name` AS healthcare_facility_name FROM {$this->table} INNER JOIN `patient` ON {$this->table}.`patient_id` = `patient`.`id` INNER JOIN `location` ON `location`.`id` = `{$this->table}`.`location_id` INNER JOIN `healthcare_facility` ON `healthcare_facility`.`id` = `{$this->table}`.`admit_from_id` WHERE {$this->table}.`referral_date` >= :datetime_start AND {$this->table}.`referral_date` <= :datetime_end AND {$this->table}.`location_id` = :location_id AND ({$this->table}.`status` = 'Approved' OR {$this->table}.`status` = 'Pending')";

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

		$sql = "SELECT `{$this->table}`.*, `{$this->table}`.`public_id` AS schedule_pubid, `patient`.`last_name`, `patient`.`first_name` FROM `{$this->table}` INNER JOIN `patient` ON {$this->table}.`patient_id` = `patient`.`id` WHERE {$this->table}.`datetime_discharge` >= :datetime_start AND {$this->table}.`datetime_discharge` <= :datetime_end AND {$this->table}.`location_id` = :location_id AND {$this->table}.`status` = :status ORDER BY :orderby";
		$params = array(
			':datetime_start' => $datetime_start,
			':datetime_end' => $datetime_end,
			':location_id' => $location,
			':status' => $status,
			':orderby' => $orderby
		);

		return $this->fetchAll($sql, $params);

	}


	public function fetchByPatientId($id) {
		$table = $this->fetchTable();
		$sql = "SELECT `{$table}`.* FROM `{$table}` WHERE `{$table}`.`patient_id` = :id ORDER BY `{$table}`.referral_date DESC";
		$params[":id"] = $id;
		return $this->fetchOne($sql, $params);
	}



	public function fetchReCertList($location_id, $all = false) {
		$start_date = date('Y-m-d 00:00:01', strtotime("now - 57 days"));
		$end_date = date('Y-m-d 23:59:59', strtotime("now - 67 days"));
		$sql = "SELECT {$this->table}.*, patient.* FROM {$this->table} INNER JOIN patient ON patient.id = {$this->table}.patient_id WHERE ({$this->table}.start_of_care BETWEEN :end_date AND :start_date) AND {$this->table}.status = 'Approved' AND";

		if ($all) {
			$sql .= " home_health_schedule.location_id IN (SELECT facility_id FROM hh_facility_link WHERE home_health_id = :location_id)";
		} else {
			$sql .= " `home_health_schedule`.`location_id` = :location_id";
		}

		$params = array(
			":start_date" => $start_date,
			":end_date" => $end_date,
			":location_id" => $location_id
		);
		return $this->fetchAll($sql, $params);
	}

}