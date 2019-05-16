<?php


class DietaryController extends MainPageController {

	// protected $template = "dietary";
	public $module = "Dietary";
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
		if ($location->location_type != 4) {
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


		smarty()->assign('currentPatients', $currentPatients);
		smarty()->assign('modEnabled', $modEnabled);
	}


	public function syncAdmissions() {
		$location = $this->loadModel('Location', input()->location);
		$this->loadModel('AdmissionDashboard')->syncDBs($location->id);
	}



	public function normalizeMenuItems($menuItems) {
		$menuWeek = false;
		foreach ($menuItems as $key => $item) {

			if (isset ($item->date) && $item->date != "") {
				$menuItems[$key]->type = "MenuMod";
			} elseif (isset ($item->menu_item_id) && $item->menu_item_id != "") {
				$menuItems[$key]->type = "MenuChange";
			} else {
				$menuItems[$key]->type = "MenuItem";
			}

			// Get the current week
			$menuWeek = floor($item->day / 7);

			$menuItems[$key]->content = nl2br($item->content);

			// explode the tags
			if (strstr($item->content, "<p>")) {
				$menuItems[$key]->content = explode("<p>", $item->content);
				$menuItems[$key]->content = str_replace("</p>", "", $item->content);
			} else {
				$menuItems[$key]->content = explode("<br />", $item->content);
			}

			if (isset ($item->mod_content)) {
				// explode the tags
				if (strstr($item->mod_content, "<p>")) {
					$menuItems[$key]->mod_content = explode("<p>", $item->mod_content);
					$menuItems[$key]->mod_content = str_replace("</p>", "", $item->mod_content);
				} else {
					$menuItems[$key]->mod_content = explode("<br />", $item->mod_content);
				}
			}


		}

		smarty()->assign('count', 0);
		smarty()->assign('menuWeek', $menuWeek);
		smarty()->assignByRef('menuItems', $menuItems);
	}

}
