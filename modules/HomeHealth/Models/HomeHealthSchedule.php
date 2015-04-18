<?php

class HomeHealthSchedule extends HomeHealth {

	protected $table = 'schedule';
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

	public function fetchAdmits($datetime_start, $datetime_end, $locations = array(), $orderby = null) {
		if ($orderby != null) {
			$sql .= " ORDER BY :orderby";
			$params[':orderby'] = $orderby;
		}

		$params = array(
			':datetime_start' => date('Y-m-d 00:00:01', strtotime($datetime_start)),
			':datetime_end' => date('Y-m-d 23:59:59', strtotime($datetime_end)),
		);


		$sql = "SELECT `{$this->tableName()}`.*, {$this->tableName()}.public_id AS hh_public_id, `ac_patient`.*, `ac_location`.`name` AS location_name, `ac_healthcare_facility`.`name` AS healthcare_facility_name FROM {$this->tableName()} INNER JOIN `ac_patient` ON {$this->tableName()}.`patient_id` = `ac_patient`.`id` INNER JOIN `ac_location` ON `ac_location`.`id` = `{$this->tableName()}`.`location_id` INNER JOIN `ac_healthcare_facility` ON `ac_healthcare_facility`.`id` = `{$this->tableName()}`.`admit_from_id` WHERE {$this->tableName()}.`referral_date` >= :datetime_start AND {$this->tableName()}.`referral_date` <= :datetime_end AND {$this->tableName()}.`location_id` IN (";

		

		
		foreach ($locations as $key => $location) {
			$params[":location{$key}"] = $location->id;
			$sql .= ":location{$key}, ";
		}

		$sql = trim($sql, ", ");

		$sql .= ") AND ({$this->tableName()}.`status` = 'Approved' OR {$this->tableName()}.`status` = 'Pending' OR {$this->tableName()}.status = 'Under Consideration')";
		return $this->fetchAll($sql, $params, $this);
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
			$orderby = 'datetime_discharge ASC';
		}


		$params = array(
			':datetime_start' => $datetime_start,
			':datetime_end' => $datetime_end,
			':status' => $status,
			':orderby' => $orderby
		);


		$sql = "SELECT `{$this->tableName()}`.*, `{$this->tableName()}`.`public_id` AS schedule_pubid, `ac_patient`.`last_name`, `ac_patient`.`first_name` FROM `{$this->tableName()}` INNER JOIN `ac_patient` ON {$this->tableName()}.`patient_id` = `ac_patient`.`id` WHERE {$this->tableName()}.`datetime_discharge` >= :datetime_start AND {$this->tableName()}.`datetime_discharge` <= :datetime_end";

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

}