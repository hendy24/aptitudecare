<?php

class AdmissionDashboard extends AppModel {

	protected $table = 'schedule';


	public function fetchDischarges($datetime_start, $datetime_end, $location) {
		$sql = "SELECT " . db()->dbname2 . ".patient_admit.*, " . db()->dbname2 . ".schedule.*, " . db()->dbname2 . ".schedule.pubid as schedule_pubid, " . db()->dbname2 . ".patient_admit.pubid as patient_pubid, " . db()->dbname2 . ".facility.name AS healthcare_facility_name FROM " . db()->dbname2 . ".`patient_admit` INNER JOIN " . db()->dbname2 . ".`schedule` ON " . db()->dbname2 . ".`schedule`.`patient_admit` = " . db()->dbname2 . ".`patient_admit`.`id` INNER JOIN " . db()->dbname2 . ".facility ON " . db()->dbname2 . ".facility.id = " . db()->dbname2 . ".schedule.facility WHERE " . db()->dbname2 . ".`schedule`.datetime_discharge >= :datetime_start AND " . db()->dbname2 . ".`schedule`.datetime_discharge <= :datetime_end AND " . db()->dbname2 . ".`schedule`.facility = :facility";

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

			//	If the items have already been saved to the db then get them.
			$schedule = $scheduleObj->fetchById($r->schedule_pubid);
			$patient = $patientObj->fetchById($r->patient_pubid);

			//	If the schedule is empty then this is a new object, we are going to set the start of care
			//	date to be equal to the discharge date
			if (empty ($schedule)) {
				$schedule = new HomeHealthSchedule();
				$schedule->start_of_care = $r->datetime_discharge;
			} else {
				//	it is possible that the start of care date has been modified in the home health application
				//	and the discharge date has been changed in the admission application.  If that has happened
				//	we need to reset the start of care date to match the new discharge date
				if ($schedule->referral_date !== $r->datetime_discharge) {
					$schedule->start_of_care = $r->datetime_discharge;
				}
			}

			//	This will update the referral date if the discharge date changes
			$schedule->referral_date = $r->datetime_discharge;

			

			if (empty ($patient)) {
				$patient = new Patient();
			}
			
			$schedule->public_id = $r->schedule_pubid;
			$schedule->admit_from_id = $r->facility;
			$schedule->referred_by_type = "ahc_facility";
			$schedule->location_id = $location;
			$schedule->datetime_created = mysql_datetime();
			$schedule->datetime_modified = mysql_datetime();
			$schedule->inpatient_diagnosis = $r->discharge_comment;
			$schedule->surgeon_id = $r->ortho_id;

			if ($r->service_disposition == "AHC Home Health") {
				$schedule->confirmed = true;
			} else {
				$schedule->confirmed = false;
			}

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
			$patient->medicare_number = $r->medicare_number;




			$patient_id = $patient->save();
			if (isset ($patient->id)) {
				$patient_id = $patient->id;
				$schedule->patient_id = $patient->id;
			} else {
				$schedule->patient_id = $patient_id;
			}

			for ($i = 0; $i <= 9; $i++) {
				$file = "notes_file{$i}";
				$name = "notes_name{$i}";
				
				$patient_notes = new PatientNote;
				$patient_notes->patient_id = $patient_id;
				$patient_notes->file = $r->$file;
				$patient_notes->name = $r->$name;
				if ($patient_notes->file != null) {
					$patient_notes->save();
				}
				
			}
			$schedule->save();
			

		}
		
		return true;
		
	}
}