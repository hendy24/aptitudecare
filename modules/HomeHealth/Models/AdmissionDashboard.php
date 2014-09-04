<?php

class AdmissionDashboard extends AppModel {

	protected $table = 'schedule';


	public function fetchDischarges($datetime_start, $datetime_end, $location) {
		$sql = "SELECT " . db()->dbname2 . ".patient_admit.*, " . db()->dbname2 . ".schedule.*, " . db()->dbname2 . ".schedule.pubid as schedule_pubid, " . db()->dbname2 . ".patient_admit.pubid as patient_pubid, " . db()->dbname2 . ".facility.name AS healthcare_facility_name FROM " . db()->dbname2 . ".`patient_admit` INNER JOIN " . db()->dbname2 . ".`schedule` ON " . db()->dbname2 . ".`schedule`.`patient_admit` = " . db()->dbname2 . ".`patient_admit`.`id` INNER JOIN " . db()->dbname2 . ".facility ON " . db()->dbname2 . ".facility.id = " . db()->dbname2 . ".schedule.facility WHERE " . db()->dbname2 . ".`schedule`.`status` = 'Discharged' AND " . db()->dbname2 . ".`schedule`.datetime_discharge >= :datetime_start AND " . db()->dbname2 . ".`schedule`.datetime_discharge <= :datetime_end AND " . db()->dbname2 . ".`schedule`.facility = :facility";

		$params = array(
			":datetime_start" => date('Y-m-d 00:00:01', strtotime($datetime_start)),
			":datetime_end" => date('Y-m-d 23:59:59', strtotime($datetime_end)),
			":facility" => $location
		);

		$result =  $this->fetchAll($sql, $params);

		//	Map data to correct columns for the new db and save the rows, they can then be fetched with a single query
		foreach ($result as $r) {

			//	Check if the patient data has already been synchronized

			$scheduleObj = new HomeHealthSchedule();
			$patientObj = new Patient();
			$schedule = $scheduleObj->fetchById($r->schedule_pubid);
			$patient = $patientObj->fetchById($r->patient_pubid);

			if (empty ($schedule)) {
				$schedule = new HomeHealthSchedule();
			}

			if (empty ($patient)) {
				$patient = new Patient();
			}
			
			$schedule->public_id = $r->schedule_pubid;
			$schedule->admit_from_id = $r->facility;
			$schedule->datetime_admit = $r->datetime_discharge;
			$schedule->referred_by_type = "ahc_facility";
			$schedule->location_id = $location;

			$patient->public_id = $r->patient_pubid;
			$patient->first_name = $r->first_name;
			$patient->last_name = $r->last_name;
			$patient->middle_name = $r->middle_name;
			$patient->address = $r->address;
			$patient->city = $r->city;
			$patient->state = $r->state;
			$patient->zip = $r->zip;
			$patient->phone = $r->phone;
			$patient->sex = $r->sex;
			$patient->date_of_birth = $r->birthday;
			$patient->ethnicity = $r->ethnicity;
			$patient->marital_status = $r->marital_status;
			$patient->religion = $r->religion;
			$patient->ssn = $r->ssn;
			$patient->emergency_contact = $r->emergency_contact_name1;
			$patient->emergency_phone = $r->emergency_contact_phone1;
			$patient->pcp_id = $r->physician_id;


			$patient_id = $patient->save();
			if (isset ($patient->id)) {
				$schedule->patient_id = $patient->id;
			} else {
				$schedule->patient_id = $patient_id;
			}
			
			$schedule->save();
			

		}
		
		return true;
		
	}
}