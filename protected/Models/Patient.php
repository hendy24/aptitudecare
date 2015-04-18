<?php

class Patient extends AppData {

	protected $table = 'patient';


	public function fetchCurrentPatients($location, $order_by = false) {
		$schedule = $this->loadTable('HomeHealthSchedule');
		$physician = $this->loadTable('Physician');
		$sql = "SELECT p.*, s.`public_id` AS schedule_pubid, s.`referral_date`, s.`datetime_discharge`, CONCAT(physician.`last_name`, ', ', physician.`first_name`) AS physician_name FROM {$this->tableName()} p INNER JOIN {$schedule->tableName()} AS s ON s.`patient_id` = p.`id` LEFT JOIN {$physician->tableName()} AS physician ON physician.`id` = s.`pcp_id` WHERE s.`status` = 'Approved'";


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

		$sql .= "AND s.location_id IN (:location)";

		

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
		$sql = "SELECT `{$this->tableName()}`.*, `home_health_schedule`.`public_id` AS schedule_pubid, ac_healthcare_facility.name AS referral_source, `home_health_schedule`.`referral_date`, `home_health_schedule`.`start_of_care`, `home_health_schedule`.`datetime_discharge`, home_health_schedule.clinicians_assigned, home_health_schedule.f2f_received, home_health_schedule.status, CONCAT(`ac_physician`.`last_name`, ', ', `ac_physician`.`first_name`) AS physician_name FROM `{$this->tableName()}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->tableName()}`.`id` LEFT JOIN `ac_physician` ON `ac_physician`.`id` = `home_health_schedule`.`following_physician_id` LEFT JOIN ac_healthcare_facility ON home_health_schedule.referred_by_id = ac_healthcare_facility.id WHERE (`home_health_schedule`.`status` = 'Approved' OR (`home_health_schedule`.`status` = 'Discharged' AND `home_health_schedule`.`datetime_discharge` >= :datetime)) AND ";

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


	public function fetchPendingAdmits($location_id) {
		$sql = "SELECT {$this->tableName()}.*, home_health_schedule.*, ac_location.name AS location_name, CONCAT(ac_physician.last_name, ', ', ac_physician.first_name) AS physician_name FROM {$this->tableName()} INNER JOIN home_health_schedule ON home_health_schedule.patient_id = ac_patient.id INNER JOIN ac_location ON ac_location.id = home_health_schedule.admit_from_id LEFT JOIN ac_physician ON ac_physician.id = home_health_schedule.pcp_id WHERE home_health_schedule.location_id = :location_id";
		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function fetchPatientSearch($term) {
		if ($term != '') {
			$tokens = explode(' ', $term);
			$params = array();

			foreach ($tokens as $idx => $token) {
				$token = trim($token);
				$params[":term{$idx}"] = "%{$token}%";
				$sql = "(SELECT `{$this->tableName()}`.*, `home_health_schedule`.`referral_date`, `home_health_schedule`.`datetime_discharge`, home_health_schedule.clinicians_assigned, home_health_schedule.f2f_received, `home_health_schedule`.`status` FROM `{$this->tableName()}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->tableName()}`.`id` WHERE `{$this->tableName()}`.`first_name` LIKE :term{$idx} OR `{$this->table}`.`last_name` LIKE :term{$idx}) UNION ";
			}

			$sql = trim($sql, ' UNION');
			return $this->fetchAll($sql, $params);


		}
	}

}