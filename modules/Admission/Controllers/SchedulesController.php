<?php

class SchedulesController extends AdmissionController {


	public function dischargePatient() {
		if (input()->id != "") {
			$patient = $this->loadModel('Patient', input()->id);
			$schedule = $this->loadModel('Schedule');
			if ($schedule->discharge($patient->id)) {
				return true;
			}
			
		}

		return false;
	}
	
	//AJAX END POINT FOR ROOM CHANGE!
	public function movePatientRooms() {
		if (input()->id != "") {
			$patient = $this->loadModel("Patient", input()->id);
			$schedule = $this->loadModel('Schedule');
			if ($schedule->move($this->getLocation()->id, $patient->id, input()->oldroom, input()->newroom)) {
				return true;
			}
			
		} else {
			return false;
		}
		
		$data = ""; //print_r($currentPatients, true);
		
		$this->template = 'ajax';
		smarty()->assign('data', $data);
	}
}
