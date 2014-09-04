<?php

class Patient extends AppModel {

	protected $table = 'patient';


	public function fetchCurrentPatients($location_id, $order_by = false) {
		$sql = "SELECT `{$this->table}`.*, `home_health_schedule`.`public_id` AS schedule_pubid, `home_health_schedule`.`datetime_admit`, `home_health_schedule`.`datetime_discharge`, CONCAT(`physician`.`last_name`, ', ', `physician`.`first_name`) AS physician_name FROM `{$this->table}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->table}`.`id` LEFT JOIN `physician` ON `physician`.`id` = `home_health_schedule`.`pcp_id` WHERE `home_health_schedule`.`status` = 'Approved' AND `home_health_schedule`.`location_id` = :location_id";

		$sql .= " ORDER BY ";
		switch ($order_by) {
			case "patient_name":
				$sql .= " `{$this->table}`.`last_name` ASC";
				break;

			case "admit_date":
				$sql .=" `home_health_schedule`.`datetime_admit` ASC";
				break;

			case "discharge_date":
				$sql .=" `home_health_schedule`.`datetime_discharge` ASC";
				break;

			case "pcp":
				$sql .=" `physician_name` ASC";
				break;

			case "pt":
				$sql .=" `clinician`.`last_name` ASC";
				break;

			default:
				$sql .=" `home_health_schedule`.`datetime_admit` ASC";
				break;
				
		}

		$params[":location_id"] = $location_id;
		return $this->fetchAll($sql, $params);
	}


	public function fetchCensusPatients($location_id, $order_by = false) {
		$sql = "SELECT `{$this->table}`.*, `home_health_schedule`.`public_id` AS schedule_pubid, `home_health_schedule`.`datetime_admit`, `home_health_schedule`.`datetime_discharge`, CONCAT(`physician`.`last_name`, ', ', `physician`.`first_name`) AS physician_name FROM `{$this->table}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->table}`.`id` LEFT JOIN `physician` ON `physician`.`id` = `home_health_schedule`.`pcp_id` WHERE (`home_health_schedule`.`status` = 'Approved' OR (`home_health_schedule`.`status` = 'Discharged' AND `home_health_schedule`.`datetime_discharge` >= :datetime)) AND `home_health_schedule`.`location_id` = :location_id";

		$sql .= " ORDER BY ";
		switch ($order_by) {
			case "patient_name":
				$sql .= " `{$this->table}`.`last_name` ASC";
				break;

			case "admit_date":
				$sql .=" `home_health_schedule`.`datetime_admit` ASC";
				break;

			case "discharge_date":
				$sql .=" `home_health_schedule`.`datetime_discharge` ASC";
				break;

			case "pcp":
				$sql .=" `physician_name` ASC";
				break;

			case "pt":
				$sql .=" `clinician`.`last_name` ASC";
				break;

			default:
				$sql .=" `{$this->table}`.`last_name` ASC, `home_health_schedule`.`datetime_admit` ASC";
				break;
				
		}

		$params[":location_id"] = $location_id;
		$params[":datetime"] = date('Y-m-d H:i:s', strtotime('now'));

		return $this->fetchAll($sql, $params);
	}


	public function fetchPendingAdmits($location_id) {
		$sql = "SELECT {$this->table}.*, home_health_schedule.*, location.name AS location_name, CONCAT(physician.last_name, ', ', physician.first_name) AS physician_name FROM {$this->table} INNER JOIN home_health_schedule ON home_health_schedule.patient_id = patient.id INNER JOIN location ON location.id = home_health_schedule.admit_from_id LEFT JOIN physician ON physician.id = home_health_schedule.pcp_id WHERE home_health_schedule.location_id = :location_id";
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
				$sql = "(SELECT `{$this->table}`.*, `home_health_schedule`.`datetime_admit`, `home_health_schedule`.`datetime_discharge`, `home_health_schedule`.`status` FROM `{$this->table}` INNER JOIN `home_health_schedule` ON `home_health_schedule`.`patient_id` = `{$this->table}`.`id` WHERE `{$this->table}`.`first_name` LIKE :term{$idx} OR `{$this->table}`.`last_name` LIKE :term{$idx}) UNION ";
			}

			$sql = trim($sql, ' UNION');
			return $this->fetchAll($sql, $params);


		}
	}

}