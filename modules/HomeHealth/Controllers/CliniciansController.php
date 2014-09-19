<?php

class CliniciansController extends MainController {

	public function manage() {		

		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				$area = $this->loadModel('Location', input()->area);
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		smarty()->assign('loc', $location);
		smarty()->assign('area', $area);


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