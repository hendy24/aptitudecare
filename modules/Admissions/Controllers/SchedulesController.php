<?php

class SchedulesController extends AdmissionsController {


	public function dischargePatient() {
		if (isset (input()->id)) {
			$client = $this->loadModel('Client', input()->id);
			$schedule = $this->loadModel('Schedule');
			if ($schedule->discharge($client->id)) {
				return true;
			}
			
		} 

		return false;
	}
}