<?php

class Client extends AppData {

	protected $table = 'client';

	// this function will get the current home health patients
	public function fetchCurrentPatients($location, $order_by = false) {
		$schedule = $this->loadTable('HomeHealthSchedule');
		$physician = $this->loadTable('Physician');
		$sql = "SELECT p.*, s.`public_id` AS schedule_pubid, s.`referral_date`, s.`datetime_discharge`, CONCAT(physician.`last_name`, ', ', physician.`first_name`) AS physician_name FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.`patient_id` = p.`id` LEFT JOIN {$physician->tableName()} AS physician ON physician.`id` = s.`pcp_id` WHERE s.`status` = 'Approved' AND s.location IN (";


		if (is_array($location)) {
			foreach ($location as $key => $location) {
				$params[":location{$key}"] = $location->id;
				$sql .= ":location{$key}, ";
			}

			$sql = trim($sql, ", ");
			$sql .= " )";
		} else {
			$params[":location"] = $location;
			$sql .= " :location)";
		}

		$sql .= " ORDER BY ";
		switch ($order_by) {
			case "patient_name":
				$sql .= " p.`last_name` ASC";
				break;

			case "admit_date":
				$sql .=" s.`referral_date` ASC";
				break;

			case "discharge_date":
				$sql .=" s.`datetime_discharge` ASC";
				break;

			case "pcp":
				$sql .=" physician ASC";
				break;

			case "pt":
				$sql .=" `home_health_clinician`.`last_name` ASC";
				break;

			default:
				$sql .=" s.`referral_date` ASC";
				break;

		}
		return $this->fetchAll($sql, $params);
	}


	/*
	 * CURRENT PATIENTS FROM NEW DATABASE
	 *
	 * This function selects the current patients from the new master database (not the admissions db). After the admission
	 * app is rebuilt the patient_admit table will need to be updated with more rows and this query will need to be updated
	 * to properly fetch the patient info.
	 *
	 * Used on (DietaryController.php => line 47)
	 */
	public function fetchPatients($location) {
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$sql = "SELECT p.*, s.id AS patient_admit_id, s.location, s.status, r.number FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.client = p.id INNER JOIN {$room->tableName()} AS r ON r.id = s.room WHERE s.location = :location AND (s.datetime_discharge >= :datetime OR s.datetime_discharge IS NULL)";
		$params[":location"] = $location;
		$params["datetime"] = mysql_date() . " 23:59:59";
		return $this->fetchAll($sql, $params);
	}

	public function fetchPatientById($patient_id) {
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$sql = "SELECT p.*, s.id AS patient_admit_id, s.location, s.status, r.number FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.client = p.id INNER JOIN {$room->tableName()} AS r ON r.id = s.room WHERE p.public_id = :patient_id";
		$params[":patient_id"] = $patient_id;
		return $this->fetchOne($sql, $params);
	}



	public function fetchPreviousPatients($last_name = null, $first_name = null, $middle_name = null) {

		$location = $this->loadTable("Location");
		$schedule = $this->loadTable("HomeHealthSchedule");

		$sql = "SELECT p.`public_id`, p.`last_name`, p.`first_name`, p.`middle_name`, p.`date_of_birth`, l.`name` AS location_name, s.`datetime_discharge`, s.`status` FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON p.`id`=s.`client` INNER JOIN {$location->tableName()} AS l ON l.`id`=s.`location` WHERE p.`first_name` LIKE :first_name AND p.`last_name` LIKE :last_name";
		if (input()->middle_name != '') {
			$sql .= " AND p.`middle_name` LIKE :middle_name";
			$params[":middle_name"] = "%" . $middle_name . "%";
		}
		$params = array(
			":first_name" => "%" . $first_name . "%",
			":last_name" => "%" . $last_name . "%",
		);

		return $this->fetchAll($sql, $params);
	}


	public function fetchCensusPatients($location_id, $order_by = false, $all = false) {
		$sql = "SELECT `{$this->tableName()}`.*, `home_health_schedule`.`public_id` AS schedule_pubid, ac_healthcare_facility.name AS referral_source, `home_health_schedule`.`referral_date`, `home_health_schedule`.`start_of_care`, `home_health_schedule`.`datetime_discharge`, home_health_schedule.clinicians_assigned, home_health_schedule.f2f_received, home_health_schedule.status, CONCAT(`ac_physician`.`last_name`, ', ', `ac_physician`.`first_name`) AS physician_name FROM `{$this->tableName()}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->tableName()}`.`id` LEFT JOIN `ac_physician` ON `ac_physician`.`id` = `home_health_schedule`.`following_physician_id` LEFT JOIN ac_healthcare_facility ON home_health_schedule.referred_by_location_id = ac_healthcare_facility.id WHERE (`home_health_schedule`.`status` = 'Approved' OR (`home_health_schedule`.`status` = 'Discharged' AND `home_health_schedule`.`datetime_discharge` >= :datetime)) AND ";

		if ($all) {
			$sql .= " home_health_schedule.location_id IN (SELECT facility_id FROM home_health_facility_link WHERE home_health_id = :location_id)";
		} else {
			$sql .= " `home_health_schedule`.`location_id` = :location_id";
		}

		$sql .= " ORDER BY ";
		switch ($order_by) {
			case "patient_name":
				$sql .= " `{$this->tableName()}`.`last_name` ASC";
				break;

			case "admit_date":
				$sql .=" `home_health_schedule`.`referral_date` ASC";
				break;

			case "discharge_date":
				$sql .=" `home_health_schedule`.`datetime_discharge` ASC";
				break;

			case "pcp":
				$sql .=" `ac_physician_name` ASC";
				break;

			case "referral_source":
				$sql .= " ac_healthcare_facility.name ASC";
				break;

			case "phone":
				$sql .= "ac_patient.phone ASC";
				break;

			case "zip":
				$sql .= "ac_patient.zip ASC";
				break;

			case "pt":
				$sql .=" `home_health_clinician`.`last_name` ASC";
				break;

			default:
				$sql .=" `{$this->tableName()}`.`last_name` ASC, `home_health_schedule`.`referral_date` ASC";
				break;

		}


		$params[":location_id"] = $location_id;
		$params[":datetime"] = date('Y-m-d H:i:s', strtotime('now'));

		return $this->fetchAll($sql, $params);
	}



	/*
	 * Fetch Patients for the 90-Day Census
	 *
	 */
	public function fetch90DayCensusPatients($location_id, $order_by = false, $all = false) {
		$schedule = $this->loadTable('HomeHealthSchedule');
		$physician = $this->loadTable('Physician');
		$healthcare_facility = $this->loadTable('HealthcareFacility');
		$sql = "SELECT
			p.*,
			s.public_id AS schedule_pubid,
			hc_f.name AS referral_source,
			s.clinicians_assigned,
			s.referral_date,
			s.start_of_care,
			s.datetime_discharge,
			s.f2f_received,
			s.status,
			CONCAT(physician.last_name, ', ', physician.first_name) AS physician_name

			FROM {$this->tableName()} p

			INNER JOIN {$schedule->tableName()} AS s ON s.patient_id = p.id
			LEFT JOIN {$physician->tableName()} AS physician ON physician.id = s.following_physician_id
			LEFT JOIN {$healthcare_facility->tableName()} as hc_f ON s.referred_by_location_id = hc_f.id

			WHERE s.referral_date >= :datetime_start
			AND s.referral_date <= :datetime_end
			AND (s.status = 'Approved' OR s.status = 'Discharged')
			AND ";

		if ($all) {
			$sql .= " s.location_id IN (SELECT facility_id FROM home_health_facility_link WHERE home_health_id = :location_id)";
		} else {
			$sql .= " s.location_id = :location_id";
		}

		$sql .= " ORDER BY ";
		switch ($order_by) {
			case "patient_name":
				$sql .= " p.last_name ASC";
				break;

			case "admit_date":
				$sql .=" s.referral_date ASC";
				break;

			case "discharge_date":
				$sql .=" s.datetime_discharge ASC";
				break;

			case "pcp":
				$sql .=" physician.last_name ASC";
				break;

			case "referral_source":
				$sql .= " ac_healthcare_facility.name ASC";
				break;

			default:
				$sql .=" p.last_name ASC, s.referral_date ASC";
				break;

		}

		$params[":location_id"] = $location_id;
		$params[":datetime_start"] = date('Y-m-d H:i:s', strtotime("now - 90 days"));
		$params[":datetime_end"] = date('Y-m-d H:i:s', strtotime("now"));

		return $this->fetchAll($sql, $params);

	}




	public function fetchPatientSearch($term, $locations = false) {
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();

			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$params[":term{$idx}"] = "%{$token}%";

				// load other database tables
				$hhs = $this->loadTable("HomeHealthSchedule");
				$hf = $this->loadTable("HealthcareFacility");
				$p = $this->loadTable("Physician");

				$sql = "SELECT patient.*, hhs.referral_date, hhs.start_of_care, hhs.datetime_discharge, hhs.clinicians_assigned, hhs.f2f_received, hhs.status, hf.name, p.last_name AS physician_last, p.first_name AS physician_first FROM `{$this->tableName()}` patient INNER JOIN {$hhs->tableName()} hhs ON hhs.`patient_id` = patient.`id` LEFT JOIN {$hf->tableName()} hf ON hf.id = hhs.referred_by_location_id LEFT JOIN {$p->tableName()} p ON p.id = hhs.following_physician_id WHERE patient.`first_name` LIKE :term{$idx} OR patient.`last_name` LIKE :term{$idx} UNION ";
			}

			$sql = trim($sql, ' UNION');

			if (is_array($locations)) {
				$sql .= " AND hhs.location_id IN (";

				foreach ($locations as $key => $location) {
					$params[":location{$key}"] = $location->id;
					$sql .= ":location{$key}, ";
				}

				$sql = trim($sql, ", ");
				$sql .= " )";
			} else {
				$params[":location"] = $location;
				$sql .= " :location)";
			}

			$result = $this->fetchAll($sql, $params);

			if (!empty($result)) {
				return $result;
			}

			return false;


		}
	}

	/* 
	 * Fetch all current prospects
	 *
	 */
	public function fetchProspects($filter_by, $sort_by = false) {
		$timeframe = $this->loadTable('Timeframe');
		$contact_link = $this->loadTable('ContactLink');
		$contact = $this->loadTable('Contact');
		$location = $this->loadTable('Location');
		$schedule = $this->loadTable('Schedule');
		$room = $this->loadTable('Room');
		$status = $this->loadTable('Status');

		// if ($date) {
		// 	$params[":date"] = mysql_date($date);
		// } else {
		// 	$params[":date"] = date('Y-m-d', strtotime("now"));
		// }

		$params = array(
			":status" => $filter_by
		);
		
		$sql = "SELECT 
				client.id,
				client.public_id,
				client.first_name,
				client.last_name,
				client.email,
				client.phone,
				schedule.timeframe,
				contact.first_name as contact_first_name,
				contact.last_name as contact_last_name,
				contact.email as contact_email,
				contact.phone as contact_phone,
				room.number,
				status.name as status,
				schedule.datetime_admit
				FROM {$this->tableName()} as client
				INNER JOIN {$schedule->tableName()} as schedule ON schedule.client = client.id
				INNER JOIN {$timeframe->tableName()} as timeframe ON timeframe.id = schedule.timeframe
				INNER JOIN {$status->tableName()} as status ON status.id = schedule.status
				LEFT JOIN (SELECT client, contact, {$contact_link->tableName()}.primary_contact FROM {$contact_link->tableName()} WHERE {$contact_link->tableName()}.primary_contact = 1) cl ON cl.client = client.id
				LEFT JOIN {$contact->tableName()} as contact ON contact.id = cl.contact AND cl.primary_contact = 1
				LEFT JOIN {$room->tableName()} as room ON room.id = schedule.room
				WHERE status.description = :status";

				if ($sort_by) {
					if ($sort_by == 'room') {
						$sql .= " ORDER BY number ASC";
					} elseif ($sort_by == 'contact') {
						$sql .= " ORDER BY contact_last_name ASC";
					} elseif ($sort_by == 'resident_name') {
						$sql .= " ORDER BY last_name ASC";
					} else {
						$sql .= " ORDER BY timeframe.id ASC";
					}					
				} else {
					$sql .=" ORDER BY timeframe.id ASC";
				}
				
		return $this->fetchAll($sql, $params);
	}

}
