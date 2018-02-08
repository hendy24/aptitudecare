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
}