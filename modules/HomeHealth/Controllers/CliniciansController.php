<?php

class CliniciansController extends MainController {

	public function manage() {		

		if (isset (input()->filter)) {
			$filter = input()->filter;
		} else {
			$filter = false;
		}

		smarty()->assign('title', 'Manage Clinicians');

		$clinicianOptions = $this->loadModel('Clinician')->fetchClinicianTypes();
		$clinicianTypes = $this->loadModel('Clinician')->fetchClinicianTypes($filter);
		$clinicians = $this->loadModel('Clinician')->fetchClinicians($filter);

		smarty()->assign('clinicianTypes', $clinicianTypes);
		smarty()->assign('clinicianOptions', $clinicianOptions);
		smarty()->assign('clinicians', $clinicians);
		smarty()->assign('filter', $filter);

	}

	public function add() {
		
	}
}