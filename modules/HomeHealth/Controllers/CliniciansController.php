<?php

class CliniciansController extends MainPageController {

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

		$clinicianTypes = $this->loadModel('Clinician')->fetchClinicianTypes($filter);
		$clinicianOptions = $this->loadModel('Clinician')->fetchClinicianTypes();
		$clinicians = $this->loadModel('Clinician')->fetchClinicians($location, $filter);

		smarty()->assign('clinicianTypes', $clinicianTypes);
		smarty()->assign('clinicianOptions', $clinicianOptions);
		smarty()->assign('clinicians', $clinicians);
		smarty()->assign('filter', $filter);

	}

	public function add() {
		
	}
}