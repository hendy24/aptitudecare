<?php

class LocationsController extends MainController {


	public function census() {
		smarty()->assign('title', "Census");
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				if (input()->area != 'all') {
					$area = $this->loadModel('Location', input()->area);
				} else {
					$area = 'all';
				}
				
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		smarty()->assign('loc', $location);
		smarty()->assign('selectedArea', $area);

		
		if (isset (input()->order_by)) {
			$_order_by = input()->order_by;
		} else {
			$_order_by = false;
		}

		$this->helper = 'PatientMenu';

		//	Get currently admitted patients
		if ($area == 'all') {
			$patients = $this->loadModel('Patient')->fetchCensusPatients($location->id, $_order_by, 'all');
		} else {
			$patients = $this->loadModel('Patient')->fetchCensusPatients($area->id, $_order_by);
		}
		
		smarty()->assignByRef('patients', $patients);

	}


	public function recertification_list() {
		smarty()->assign('title', "Re-certification List");
		$this->helper = 'PatientMenu';
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				if (input()->area != 'all') {
					$area = $this->loadModel('Location', input()->area);
				} else {
					$area = 'all';
				}
				
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		smarty()->assign('loc', $location);
		smarty()->assign('selectedArea', $area);

		$schedule = $this->loadModel('HomeHealthSchedule')->fetchReCertList($area->id);
		smarty()->assignByRef('censusList', $schedule);
	}


	public function fetchAreas() {
		$areas = $this->loadModel('Location', input()->location)->fetchLinkedFacilities();
		json_return ($areas);
	}
}