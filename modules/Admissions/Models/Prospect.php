<?php

class Prospect extends Admission {

	protected $table = 'prospect';



			
	/* 
	 * Fetch all current prospects
	 *
	 */
	public function fetchProspects($date = false) {
		$patient = $this->loadTable('Patient');

		if ($date) {
			$params[":date"] = mysql_date($date);
		} else {
			$params[":date"] = date('Y-m-d', strtotime("now"));
		}
		
		$sql = "SELECT 
				patient.first_name,
				patient.last_name,
				patient.email_address,
				patient.phone,
				prospect.timeframe,
				prospect.admit_date,
				prospect.follow_up_date
				FROM {$this->tableName()} as prospect
				INNER JOIN {$patient->tableName()} as patient ON patient.id = prospect.patient
				WHERE prospect.active = 1
				ORDER BY prospect.admit_date ASC
				";


		return $this->fetchAll($sql, $params);
	}
}