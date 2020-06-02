<?php

class Prospect extends Admission {

	protected $table = 'prospect';



			
	/* 
	 * Fetch all current prospects
	 *
	 */
	public function fetchProspects($active_prospect = true) {
		$timeframe = $this->loadTable('Timeframe');
		$contact_link = $this->loadTable('ContactLink');
		$contact = $this->loadTable('Contact');

		// if ($date) {
		// 	$params[":date"] = mysql_date($date);
		// } else {
		// 	$params[":date"] = date('Y-m-d', strtotime("now"));
		// }

		$params[":active_prospect"] = $active_prospect;
		
		$sql = "SELECT 
				prospect.id,
				prospect.public_id,
				prospect.first_name,
				prospect.last_name,
				prospect.public_id,
				prospect.email,
				prospect.phone,
				prospect.timeframe,
				prospect.follow_up_date,
				contact.first_name as contact_first_name,
				contact.last_name as contact_last_name,
				contact.email as contact_email,
				contact.phone as contact_phone
				FROM {$this->tableName()} as prospect
				INNER JOIN {$timeframe->tableName()} as timeframe ON timeframe.id = prospect.timeframe
				LEFT JOIN (SELECT prospect, contact, admit_contact_link.primary_contact FROM {$contact_link->tableName()} WHERE admit_contact_link.primary_contact = 1) cl ON cl.prospect = prospect.id
				LEFT JOIN {$contact->tableName()} as contact ON contact.id = cl.contact AND cl.primary_contact = 1
				WHERE prospect.active = :active_prospect
				ORDER BY timeframe.id ASC";


		return $this->fetchAll($sql, $params);
	}
}