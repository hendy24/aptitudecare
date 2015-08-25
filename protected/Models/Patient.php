<?php

class Patient extends AppData {

	protected $table = 'patient';

	// this function will get the current home health patients
	public function fetchCurrentPatients($location, $order_by = false) {
		$schedule = $this->loadTable('HomeHealthSchedule');
		$physician = $this->loadTable('Physician');
		$sql = "SELECT p.*, s.`public_id` AS schedule_pubid, s.`referral_date`, s.`datetime_discharge`, CONCAT(physician.`last_name`, ', ', physician.`first_name`) AS physician_name FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.`patient_id` = p.`id` LEFT JOIN {$physician->tableName()} AS physician ON physician.`id` = s.`pcp_id` WHERE s.`status` = 'Approved' AND s.location_id IN (";


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
	public function fetchPatients($location_id) {
		$schedule = $this->loadTable("Schedule");
		$room = $this->loadTable("Room");
		$sql = "SELECT p.*, s.id AS patient_admit_id, s.location_id, s.status, r.number FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.patient_id = p.id INNER JOIN {$room->tableName()} AS r ON r.id = s.room_id WHERE s.location_id = :location_id AND s.status = 'Approved'";
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}



	public function fetchPreviousPatients($last_name = null, $first_name = null, $middle_name = null) {

		$location = $this->loadTable("Location");
		$schedule = $this->loadTable("HomeHealthSchedule");

		$sql = "SELECT p.`public_id`, p.`last_name`, p.`first_name`, p.`middle_name`, p.`date_of_birth`, l.`name` AS location_name, s.`datetime_discharge`, s.`status` FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON p.`id`=s.`patient_id` INNER JOIN {$location->tableName()} AS l ON l.`id`=s.`location_id` WHERE p.`first_name` LIKE :first_name AND p.`last_name` LIKE :last_name";
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


	public function fetchPendingAdmits($location_id) {
		$sql = "SELECT {$this->tableName()}.*, home_health_schedule.*, ac_location.name AS location_name, CONCAT(ac_physician.last_name, ', ', ac_physician.first_name) AS physician_name FROM {$this->tableName()} INNER JOIN home_health_schedule ON home_health_schedule.patient_id = ac_patient.id INNER JOIN ac_location ON ac_location.id = home_health_schedule.admit_from_id LEFT JOIN ac_physician ON ac_physician.id = home_health_schedule.pcp_id WHERE home_health_schedule.location_id = :location_id";
		$params[":location_id"] = $location_id;
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

}