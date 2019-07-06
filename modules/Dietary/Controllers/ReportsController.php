<?php

class ReportsController extends DietaryController {

	public $module = "Dietary";
	protected $navigation = "dietary";
	protected $searchBar = "dietary";
	protected $helper = "DietaryMenu";



/*
 * -------------------------------------------------------------------------
 * MENU CHANGES PAGE
 * -------------------------------------------------------------------------
 *
 * View the changes made by each facilities within a specified time frame.
 * This page should only be accessed by those with permissions
 *
 */
	public function menu_changes() {
		smarty()->assign('title', "Menu Changes");

		// Check if user has permission to access this page
		if (!auth()->hasPermission("view_menu_changes")) {
			session()->setFlash("You do not have permission to add new users", 'error');
			$this->redirect();
		}

		// set the time frame options for the drop down menu
		$this->reportDays();

		$url = SITE_URL . "/?module={$this->module}&page=reports&action=menu_change_details";

		if (isset (input()->days)) {
			$days = input()->days;
			$start_date = false;
			$end_date = false;
			$url .= "&days={$days}";
		} elseif (isset (input()->start_date) && isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->start_date));
			$end_date = date("Y-m-d", strtotime(input()->end_date));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} elseif (isset (input()->start_date) && !isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->start_date));
			$end_date = date("Y-m-d", strtotime("now"));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} elseif (!isset (input()->start_date) && isset (input()->end_date)) {
			$start_date = date("Y-m-d", strtotime(input()->end_date . " - 30 days"));
			$end_date = date("Y-m-d", strtotime(input()->end_date));
			$days = false;
			$url .= "&start_date={$start_date}&end_date={$end_date}";
		} else {
			$days = 30;
			$start_date = false;
			$end_date = false;
			$url .= "&days={$days}";
		}

		$menuChanges = $this->loadModel("MenuMod")->countMenuMods($days, $start_date, $end_date);
		smarty()->assignByRef("menuChanges", $menuChanges);
		smarty()->assign("url", $url);
		smarty()->assign("numDays", $days);

	}



/*
 * -------------------------------------------------------------------------
 * MENU CHANGE DETAILS
 * -------------------------------------------------------------------------
 *
 * View details regarding the changes that have been made at the selected location.
 *
 */
	public function menu_change_details() {
		smarty()->assign("title", "Menu Change Details");
		$this->reportDays();

		$location = $this->getSelectedLocation();


		if (isset (input()->days)) {
			$days = input()->days;
			$start_date = false;
			$end_date = false;
		} else {
			$days = 30;
			$start_date = false;
			$end_date = false;
		}

		if (isset (input()->page)) {
			$page = input()->page;
		} else {
			$page = false;
		}

		$results = $this->loadModel("MenuMod")->paginateMenuMods($location->id, $days, $page);
		foreach ($results as $key => $item) {
			$results[$key]->mod_content = explode("\n", $item->mod_content);
			$results[$key]->content = explode("\n", $item->content);
		}

		smarty()->assignByRef('menuItems', $results);
		smarty()->assign("numDays", $days);
	}



/*
 * -------------------------------------------------------------------------
 * BEVERAGE REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function beverages() {
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		smarty()->assign('title', "Beverages Report");
		$location = $this->getLocation();
		if (isset (input()->date)) {
			$date = date('Y-m-d', strtotime(input()->date));
		} else {
			$date = mysql_date();
		}

		// get beverages
		$beverages = $this->loadModel("Beverage")->fetchBeverageReport($location, $date);

		$bev_array = array();
		foreach ($beverages as $bev) {
			$bev_array[$bev->meal][] = array("num" => $bev->num, "name" => $bev->name);
		}

		smarty()->assign('beverages', $bev_array);
		smarty()->assign('location', $location);
		smarty()->assign('isPDF', $is_pdf);
	}



/*
 * -------------------------------------------------------------------------
 * DIET CENSUS REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function diet_census() {
		// get the location
		if (input()->location != "") {
			$location = $this->loadModel('Location', input()->location);
		} else {
			session()->setFlash("Could not get the diet census for the selected location", 'error');
			$this->redirect(array('module' => $this->module));
		}

		// // check if the location is has the admission dashboard enabled
		$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);
		smarty()->assign('modEnabled', $modEnabled);

		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		if (isset (input()->orderby)) {
			$order_by = input()->orderby;
		} else {
			$order_by = "room";
		}

		// // need to get patients room number and name with the diet order, texture and liquid
		$diet_census = $this->loadModel('PatientDietOrder')->fetchPatientCensus($location->id, $order_by);
		smarty()->assign('dietCensus', $diet_census);
		smarty()->assign('pageUrl', $this->getUrl());
		smarty()->assign('isPDF', $is_pdf);

	}

/*
 * -------------------------------------------------------------------------
 * ALLERGIES REPORT PAGE
 * -------------------------------------------------------------------------
 */

	public function allergies() {
		//echo "JASON was here.";
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		smarty()->assign('title', "Allergy Report");
		$location = $this->getLocation();
		$currentPatients = $this->loadModel('PatientInfo')->fetchByLocation_allergy($location);
		$currentPatientsDislikes = $this->loadModel('PatientInfo')->fetchByLocation_dislikes($location);
		smarty()->assignByRef('patients', $currentPatients);
		smarty()->assignByRef('patientsdislikes', $currentPatientsDislikes);
		smarty()->assign('isPDF', $is_pdf);
		unset($this->landscape_array);
	}



/*
 * -------------------------------------------------------------------------
 * PRINT SNACK LABELS
 * -------------------------------------------------------------------------
 */
	public function snack_labels() {
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}
		
		//$this->landscape_array = true;

		smarty()->assign('title', "Snack Labels");
		$location = $this->getLocation();


		smarty()->assign('location', $location);
		if (isset (input()->date)) {
			$date = date('Y-m-d', strtotime(input()->date));
		} else {
			$date = mysql_date();
		}

		// get snacks
		$snacks = $this->loadModel("Snack")->fetchSnackReport($location->id);
		smarty()->assign('snacks', $snacks);
		smarty()->assign('location', $location);
		smarty()->assign('isPDF', $is_pdf);
	}



/*
 * -------------------------------------------------------------------------
 * SNACK REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function snack_report() {
		smarty()->assign('title', "Snack Report");
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		$location = $this->getLocation();

		smarty()->assign('location', $location);
		if (isset (input()->date)) {
			$date = date('Y-m-d', strtotime(input()->date));
		} else {
			$date = mysql_date();
		}

		// get snacks
		$snacks = $this->loadModel("Snack")->fetchSnackReport($location->id);
		smarty()->assign('snacks', $snacks);
		smarty()->assign('location', $location);
		smarty()->assign('isPDF', $is_pdf);

	}




/*
 * -------------------------------------------------------------------------
 * ADAPTIVE EQUIPMENT REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function adaptive_equipment() {
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->getLocation();
		}

		$current_patients = $this->loadModel('PatientAdaptEquip')->fetchByLocation($location);
		smarty()->assignByRef('patients', $current_patients);
		smarty()->assign('isPDF', $is_pdf);
	}




// 	/*
// 	 * -------------------------------------------------------------------------
// 	 *  Common functions for report pages
// 	 * -------------------------------------------------------------------------
// 	 */
// }


	private function reportDays() {
		$numberOfDays = array(0 => "Select timeframe...", 7 => "Last 7 days", 15 => "Last 15 days", 30 => "Last 30 days", 90 => "Last 90 days", 365 => "Last 365 days");
		smarty()->assign('numberOfDays', $numberOfDays);
	}



}
