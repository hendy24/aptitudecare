<?php

class CliniciansController extends MainController {

	public function manage() {		

		if (isset (input()->location)) {
			$loc_id = input()->location;
		} else {
			//	Fetch the users default location
			$user = auth()->getRecord();
			$loc_id = $user->default_location;
		}
		$location = $this->loadModel('Location', $loc_id);
		smarty()->assign('location_id', $location->public_id);


		if (isset (input()->filter)) {
			$filter = input()->filter;
		} else {
			$filter = false;
		}

		smarty()->assign('title', 'Manage Clinicians');

		$clinicianOptions = $this->loadModel('Clinician')->fetchClinicianTypes();
		$clinicianTypes = $this->loadModel('Clinician')->fetchClinicianTypes($filter);
		$clinicians = $this->loadModel('Clinician')->fetchClinicians($location, $filter);

		smarty()->assign('clinicianTypes', $clinicianTypes);
		smarty()->assign('clinicianOptions', $clinicianOptions);
		smarty()->assign('clinicians', $clinicians);
		smarty()->assign('filter', $filter);

	}

	public function add() {
		
	}
}