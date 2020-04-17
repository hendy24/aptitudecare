<?php


class DietaryController extends MainPageController {

	// protected $template = "dietary";
	public $module = 'Dietary';
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';
	protected $helper = 'DietaryMenu';


	public function index() {
		smarty()->assign("title", "Dietary");
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}
		// get the location
		$location = $this->getLocation();

		// check if the user has permission to access this module
		if ($location->location_type != 1 && $location->location_type != 4) {
			$this->redirect();
		}


		// check if the location is has the admission dashboard enabled
		$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);

		// if the facility is using the admission dashboard, then get a list of
		// the current patients from the admission app for the current location.

		// NOTE: if a location is using the admission dashboard they should
		// not have the ability to add or delete patients through the dietary
		// app interface.
		$rooms = $this->loadModel("Room")->fetchEmpty($location->id);
		if ($modEnabled) {
			// until the admission app is re-built and we move to a single database we need to fetch
			// the data from the admission db and save to the master db
			// IMPORTANT: Remove this after admission app is re-built in new framework!!!
			$scheduled = $this->loadModel('AdmissionDashboard')->syncCurrentPatients($location->id);
		} else {
			// if the locations is not using the admission dashboard then load the patients
			// from ac_patient and dietary_patient_info tables
			// fetch current patients
			$scheduled = $this->loadModel("Patient")->fetchPatients($location->id);
		}
		$currentPatients = $this->loadModel("Room")->mergeRooms($rooms, $scheduled);

		$count = count($rooms);

		smarty()->assign('currentPatients', $currentPatients);
		smarty()->assign('count', count($rooms)/2);
		smarty()->assign('modEnabled', $modEnabled);
	}


	public function syncAdmissions() {
		$location = $this->loadModel('Location', input()->location);
		$this->loadModel('AdmissionDashboard')->syncDBs($location->id);
	}

}
