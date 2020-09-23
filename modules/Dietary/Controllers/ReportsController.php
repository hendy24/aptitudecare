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
		smarty()->assign("isPDF", false);

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
		smarty()->assign("isPDF", false);
	}



/*
 * -------------------------------------------------------------------------
 * BEVERAGE REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function beverages() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		if (@input()->pdf2 == true) {
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
			$bev_array[$bev->meal][] = array("num" => $bev->num, "name" => $bev->name, "other_id" => $bev->other_id, "liq_name" => $bev->liq_name);
		}

		smarty()->assign('beverages', $bev_array);
		smarty()->assign('location', $location);
		smarty()->assign('isPDF', $is_pdf);
		$this->pdfName = "beverages_".date("Y-m-d").".pdf";
	}

/*
 * -------------------------------------------------------------------------
 * SPECIAL REQUESTS REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function special_requests() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		if (@input()->pdf2 == true) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		smarty()->assign('title', "Special Requests Report");
		$location = $this->getLocation();
		if (isset (input()->date)) {
			$date = date('Y-m-d', strtotime(input()->date));
		} else {
			$date = mysql_date();
		}

		// get traycards to extract data from:
		@$tray_card_info = $this->loadModel('PatientInfo')->fetchTrayCardInfo(input()->patient, $location->id);
		$specialReqs = array();

		
		foreach($tray_card_info as $key => $tci)
		{
			$specialReqs[$key]["number"] = $tci->number;
			$specialReqs[$key]["name"] = $tci->last_name.", ".$tci->first_name;
			
			//create isolation flag
			if (strpos($tci->orders, "Isolation") !== false)
			{
				$tci->isolation = true;
			} else {
				$tci->isolation = false;
			}
			$specialReqs[$key]["isolation"] = $tci->isolation;
			$specialReqs[$key]["special_reqs_0"] = $tci->special_reqs_0;
			$specialReqs[$key]["special_reqs_1"] = $tci->special_reqs_1;
			$specialReqs[$key]["special_reqs_2"] = $tci->special_reqs_2;
		}
		//print_r($tray_card_info);
		//print_r($specialReqs);
		//exit();

		$sp_array = array();
		foreach ($specialReqs as $sr) {
			$sp_array[1][] = array("number" => $sr['number'], "name" => $sr['name'], "isolation" => $sr['isolation'], "special" => $sr['special_reqs_0']);
			$sp_array[2][] = array("number" => $sr['number'], "name" => $sr['name'], "isolation" => $sr['isolation'], "special" => $sr['special_reqs_1']);
			$sp_array[3][] = array("number" => $sr['number'], "name" => $sr['name'], "isolation" => $sr['isolation'], "special" => $sr['special_reqs_2']);
		}

		smarty()->assign('sp_array', $sp_array);
		smarty()->assign('location', $location);
		smarty()->assign('isPDF', $is_pdf);
		$this->pdfName = "special_requests_".date("Y-m-d").".pdf";
	}

/*
 * -------------------------------------------------------------------------
 * ISOLATION CENSUS REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function isolation() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}
		
		// get the location
		if (input()->location != "") {
			$location = $this->loadModel('Location', input()->location);
		} else {
			session()->setFlash("Could not get the isolation census for the selected location", 'error');
			$this->redirect(array('module' => $this->module));
		}

		// // check if the location is has the admission dashboard enabled
		$modEnabled = ModuleEnabled::isAdmissionsEnabled($location->id);
		smarty()->assign('modEnabled', $modEnabled);

		/*
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}*/

		if (@input()->pdf2 == true) {
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

		// // need to get patients room number and name with the diet order, texture and liquid, and other and then remove the patients not on isolation
		$diet_census = $this->loadModel('PatientDietOrder')->fetchPatientCensus($location->id, $order_by);
		foreach($diet_census as $key => $pt)
		{
			if(strpos($pt->diet_other, "Isolation") === false)
			{
				unset($diet_census[$key]);
			} else {
				//Take beverages from DB and split back to individuals
				$temp_arr = explode("[", $pt->beverages);
				$pt->beverages = array(1=>"",2=>"",3=>"");

				foreach($temp_arr as $tmp_idx => $bev)
				{
					//Skip first line.
					if($bev == ""){
						continue;
					}
					//find meals
					$temp2 = explode("]", $bev);
					$meal = $temp2[0];
					$bev_name = $temp2[1];
					$bev_name = trim($bev_name, " ,");
					
					//fix leading comma
					$sep = "";
					if($pt->beverages[$meal] != "")
					{
						$sep = ", ";
					}
					//save beverages to list for meal
					$pt->beverages[$meal].=$sep.$bev_name;
				}
			}
		}

		smarty()->assign('dietCensus', $diet_census);
		smarty()->assign('pageUrl', $this->getUrl());
		smarty()->assign('isPDF', $is_pdf);
		$this->pdfName = "isolation".date("Y-m-d").".pdf";
		$this->landscape_array = true;
		//$this->otherPDFWebkit = "--disable-smart-shrinking";

	}

/*
 * -------------------------------------------------------------------------
 * DIET CENSUS REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function diet_census() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}
		
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

		/*
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}*/

		if (@input()->pdf2 == true) {
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
		$this->pdfName = "diet_census_".date("Y-m-d").".pdf";
		$this->landscape_array = true;
		//$this->otherPDFWebkit = "--disable-smart-shrinking";

	}

/*
 * -------------------------------------------------------------------------
 * ALLERGIES REPORT PAGE
 * -------------------------------------------------------------------------
 */

	public function allergies() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		if (@input()->pdf2 == true) {
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
		$this->pdfName = "allergies_".date("Y-m-d").".pdf";
		unset($this->landscape_array);
	}



/*
 * -------------------------------------------------------------------------
 * PRINT SNACK LABELS
 * -------------------------------------------------------------------------
 */
	public function snack_labels() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		if (@input()->pdf2 == true) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}

		if (@input()->pdf2 != true) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
			$this->template = "pdf2";
			$this->margins = 0;
			if(isset(input()->pdf2))
			{
				smarty()->assign('pdf2', input()->pdf2);
			} else {
				smarty()->assign('pdf2', false);
			}
			$this->pdfName = "snack_labels_".date("Y-m-d").".pdf";
			$this->otherPDFWebkit = "--disable-smart-shrinking --margin-top 12.7 --margin-bottom 10 --margin-left 6.35 --margin-right 6.35 --dpi 300";
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
		
		//determine if we are changing date printed:
		$printDate = new DateTime();
		if(isset(input()->date))
		{
			$printDate = $printDate->createFromFormat('M d, yy', input()->date);
		}
		smarty()->assign('printDate', $printDate);
		$this->pdfName = "snack_labels_".date("Y-m-d").".pdf";
	}



/*
 * -------------------------------------------------------------------------
 * SNACK REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function snack_report() {
		smarty()->assign('title', "Snack Report");
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		if (@input()->pdf2 == true) {
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
		
		//determine if we are changing date printed:
		$printDate = new DateTime();
		if(isset(input()->date))
		{
			$printDate = $printDate->createFromFormat('M d, yy', input()->date);
		}
		smarty()->assign('printDate', $printDate);
		
		$this->pdfName = "snack_report_".date("Y-m-d").".pdf";

	}




/*
 * -------------------------------------------------------------------------
 * ADAPTIVE EQUIPMENT REPORT PAGE
 * -------------------------------------------------------------------------
 */
	public function adaptive_equipment() {
		// if user is not authorized to access this page, then re-direct
		if (!auth()->getRecord()) {
			$this->redirect();
		}

		/*
		if (!auth()->isLoggedIn()) {
			$this->template = "pdf";
			$is_pdf = true;
		} else {
			$is_pdf = false;
		}*/

		if (@input()->pdf2 == true) {
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
		$this->pdfName = "adaptive_equipment_".date("Y-m-d").".pdf";
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
