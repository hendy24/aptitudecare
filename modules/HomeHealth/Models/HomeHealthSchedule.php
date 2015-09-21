<?php

class HomeHealthSchedule extends HomeHealth {

	protected $table = 'schedule';
	// protected $belongsTo = array(
	// 	'Patient' => array(
	// 		'table' => 'patient',
	// 		'join_type' => 'inner',
	// 		'inner_key' => 'patient_id',
	// 		'foreign_key' => 'id'
	// 	)
	// );


	/*
	 * -------------------------------------------------------------------------
	 *  FETCH ADMISSIONS FOR THE WEEK
	 * -------------------------------------------------------------------------
	 */	

	public function fetchAdmits($datetime_start, $datetime_end, $locations = array(), $orderby = null) {
		if ($orderby != null) {
			$sql .= " ORDER BY :orderby";
			$params[':orderby'] = $orderby;
		}

		$params = array(
			':datetime_start' => date('Y-m-d 00:00:01', strtotime($datetime_start)),
			':datetime_end' => date('Y-m-d 23:59:59', strtotime($datetime_end)),
		);

		$patient = $this->loadTable('Patient');
		$location = $this->loadTable('Location');
		$healthcare_facility = $this->loadTable('HealthcareFacility');
		$schedule = $this->loadTable('Schedule');

		$sql = "SELECT 
			hhs.*, 
			hhs.public_id AS hh_public_id, 
			p.*, 
			s.service_disposition,
			s. discharge_location_id,
			l.`name` AS location_name, 
			hc_f.`name` AS healthcare_facility_name,
			dc_location.`name` AS dc_location

			FROM {$this->tableName()} hhs
			INNER JOIN {$patient->tableName()} AS p ON hhs.`patient_id` = p.`id` 
			INNER JOIN {$location->tableName()} AS l ON l.`id` = hhs.`location_id` 
			INNER JOIN {$schedule->tableName()} AS s ON p.id = s.patient_id
			INNER JOIN {$healthcare_facility->tableName()} AS hc_f ON hc_f.`id` = hhs.`admit_from_id` 
			LEFT JOIN {$healthcare_facility->tableName()} AS dc_location ON dc_location.`id` = s.`discharge_location_id`

			WHERE hhs.`referral_date` >= :datetime_start 
			AND hhs.`referral_date` <= :datetime_end 
			AND hhs.`location_id` IN (";

		

		
		foreach ($locations as $key => $location) {
			$params[":location{$key}"] = $location->id;
			$sql .= ":location{$key}, ";
		}

		$sql = trim($sql, ", ");

		$sql .= ") AND (hhs.`status` = 'Approved' OR hhs.`status` = 'Pending' OR hhs.status = 'Under Consideration')";

		return $this->fetchAll($sql, $params, $this);
	}



	public function fetchPendingAdmits($locations = array()) {
		$location = $this->loadTable('Location');
		$physician = $this->loadTable('Physician');
		$patient = $this->loadTable('Patient');
		$healthcare_facility = $this->loadTable('HealthcareFacility');

		// Check url for week in the past or future
		if (isset (input()->weekSeed)) {
			$weekSeed = input()->weekSeed;
		// If no date is set in the url then default to this week
		} else {
			$weekSeed = date('Y-m-d');
		}
		$week = Calendar::getWeek($weekSeed);
		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));

		// $params[":datetime_start"] = date('Y-m-d 00:00:01', strtotime($week[0]));
		// $params[":datetime_end"] = date('Y-m-d 23:59:59', strtotime($week[6]));

		$sql = "SELECT 
			hhs.*, 
			hhs.public_id AS hh_public_id, 
			p.*, 
			l.`name` AS location_name, 
			hc_f.`name` AS healthcare_facility_name 

			FROM {$this->tableName()} hhs
			INNER JOIN {$patient->tableName()} AS p ON hhs.`patient_id` = p.`id` 
			INNER JOIN {$location->tableName()} AS l ON l.`id` = hhs.`location_id` 
			INNER JOIN {$healthcare_facility->tableName()} AS hc_f ON hc_f.`id` = hhs.`admit_from_id` 
			LEFT JOIN {$physician->tableName()} AS physician ON physician.id = hhs.pcp_id 

			WHERE hhs.`location_id` IN (";

		foreach ($locations as $key => $location) {
			$params[":location{$key}"] = $location->id;
			$sql .= ":location{$key}, ";
		}
		$sql = trim($sql, ", ");
		$sql .= ") AND (hhs.confirmed = 1 AND (hhs.`status` = 'Pending' OR hhs.status = 'Under Consideration')) ORDER BY hhs.referral_date DESC";

		return $this->fetchAll($sql, $params);
	}




	/*
	 * -------------------------------------------------------------------------
	 *  FETCH DISCHARGES FOR THE WEEK
	 * -------------------------------------------------------------------------
	 */

	public function fetchDischarges($datetime_start, $datetime_end, $locations = array(), $status = null, $orderby = null) {
		if ($status == null) {
			$status = 'Scheduled Discharge';
		}

		if ($orderby == null) {
			$orderby = 'start_of_care ASC';
		}


		$params = array(
			':datetime_start' => $datetime_start,
			':datetime_end' => $datetime_end,
			':status' => $status,
			':orderby' => $orderby
		);


		$sql = "SELECT `{$this->tableName()}`.*, `{$this->tableName()}`.`public_id` AS schedule_pubid, `ac_patient`.`last_name`, `ac_patient`.`first_name` FROM `{$this->tableName()}` INNER JOIN `ac_patient` ON {$this->tableName()}.`patient_id` = `ac_patient`.`id` WHERE {$this->tableName()}.`start_of_care` >= :datetime_start AND {$this->tableName()}.`start_of_care` <= :datetime_end";

		if (is_array($locations)) {
			$locString = null;
			foreach ($locations as $l) {
				$locString .= "{$l->id}, ";
			}
			$locString = trim($locString, ", ");
			
			$params[":location"] = $locString;
		} else {
			$params[":location"] = $locations;
		}

		$sql .= " AND {$this->tableName()}.`location_id` IN (:location)";


		$sql .= " AND {$this->tableName()}.`status` = :status ORDER BY :orderby";
		return $this->fetchAll($sql, $params);

	}


	public function dischargePatients($location) {
		$sql = "UPDATE {$this->tableName()} set status = 'Discharged' WHERE start_of_care < :cut_off_date AND status = 'Approved'";

		if (is_array($location)) {
			$locString = null;
			foreach ($location as $l) {
				$locString .= "{$l->id}, ";
			}
			$locString = trim($locString, ", ");
			
			$params[":location"] = $locString;
		} else {
			$params[":location"] = $location;
		}

		$sql .= "AND location_id IN (:location)";

		$params[":cut_off_date"] = date('Y-m-d 00:00:01', strtotime("now - 60 days"));
		return $this->update($sql, $params);
	}


	public function fetchByPatientId($id) {
		$table = $this->fetchTable();
		$sql = "SELECT `{$this->tableName()}`.* FROM `{$this->tableName()}` WHERE `{$this->tableName()}`.`patient_id` = :id ORDER BY `{$this->tableName()}`.referral_date DESC";
		$params[":id"] = $id;
		return $this->fetchOne($sql, $params);
	}



	public function fetchReCertList($location_id, $all = false) {
		$start_date = date('Y-m-d 00:00:01', strtotime("now - 67 days"));
		$end_date = date('Y-m-d 23:59:59', strtotime("now - 57 days"));
		$sql = "SELECT {$this->tableName()}.*, ac_patient.* FROM {$this->tableName()} INNER JOIN ac_patient ON ac_patient.id = {$this->tableName()}.patient_id WHERE ({$this->tableName()}.start_of_care BETWEEN :start_date AND :end_date) AND ({$this->tableName()}.status = 'Approved' OR {$this->tableName()}.status = 'Discharged') AND";

		if ($all) {
			$sql .= " home_health_schedule.location_id IN (SELECT facility_id FROM home_health_facility_link WHERE home_health_id = :location_id)";
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



	public function checkExisting($location_id, $patient_id) {
		$patient = $this->loadTable("Patient");
		$sql = "SELECT * FROM {$this->tableName()} WHERE location_id = :location_id AND patient_id = (SELECT id FROM {$patient->tableName()} WHERE public_id = :patient_id) LIMIT 1";
		$params[":location_id"] = $location_id;
		$params[":patient_id"] = $patient_id;

		return $this->fetchOne($sql, $params);
	}

}