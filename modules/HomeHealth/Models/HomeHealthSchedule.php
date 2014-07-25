<?php

class HomeHealthSchedule extends Model {

	protected $table = 'home_health_schedule';


	/*
	 * -------------------------------------------------------------------------
	 *  FETCH ADMISSIONS FOR THE WEEK
	 * -------------------------------------------------------------------------
	 */	

	public function fetchAdmits($datetime_start, $datetime_end, $location, $orderby = null) {


		$sql = "SELECT * FROM {$this->table} INNER JOIN `patient` ON {$this->table}.`patient_id` = `patient`.`id` WHERE {$this->table}.`datetime_admit` >= :datetime_start AND {$this->table}.`datetime_admit` <= :datetime_end AND {$this->table}.`location_id` = :location_id AND {$this->table}.`status` = 'Approved' OR {$this->table}.`status` = 'Pending'";

		if ($orderby != null) {
			$sql .= " ORDER BY :orderby";
			$params[':orderby'] = $orderby;
		}

		$params = array(
			':datetime_start' => $datetime_start,
			':datetime_end' => $datetime_end,
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


	public function getPatient() {
		return 'hello';
	}
}