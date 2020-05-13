<?php

class Prospect extends Admission {

	protected $table = 'prospect';



			
	/* 
	 * Fetch all current prospects
	 *
	 */
	public function fetchProspects($date = false) {
		$timeframe = $this->loadTable('Timeframe');

		if ($date) {
			$params[":date"] = mysql_date($date);
		} else {
			$params[":date"] = date('Y-m-d', strtotime("now"));
		}
		
		$sql = "SELECT 
				prospect.first_name,
				prospect.last_name,
				prospect.public_id,
				prospect.email,
				prospect.phone,
				prospect.contact_name,
				prospect.contact_phone,
				prospect.contact_email,
				timeframe.name as timeframe,
				prospect.follow_up_date
				FROM {$this->tableName()} as prospect
				INNER JOIN {$timeframe->tableName()} as timeframe ON timeframe.id = prospect.timeframe
				WHERE prospect.active = 1
				ORDER BY timeframe.id ASC
				";


		return $this->fetchAll($sql, $params);
	}
}