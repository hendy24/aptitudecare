<?php

class LocationsController extends MainController {


	public function census() {
		smarty()->assign('title', "Census");
		if (isset (input()->area)) {
			$area = $this->loadModel('Location', input()->area);
		} else {
			//	Get the users default location
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		if (isset (input()->order_by)) {
			$_order_by = input()->order_by;
		} else {
			$_order_by = false;
		}

		$this->helper = 'PatientMenu';

		//	Get currently admitted patients
		$patients = $this->loadModel('Patient')->fetchCensusPatients($area->id, $_order_by);
		smarty()->assignByRef('patients', $patients);

	}
}