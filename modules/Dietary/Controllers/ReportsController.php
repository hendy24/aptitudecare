<?php

class ReportsController extends DietaryController {

	public $module = "Dietary";
	protected $navigation = "dietary";
	protected $searchBar = "dietary";
	protected $helper = "DietaryMenu";

	public function menu_changes() {
		smarty()->assign('title', "Menu Changes");
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
		$this->normalizeMenuItems($results);

		smarty()->assign("numDays", $days);
	}



	/*
	 * -------------------------------------------------------------------------
	 *  Common functions for report pages
	 * -------------------------------------------------------------------------
	 */



	private function reportDays() {
		$numberOfDays = array(0 => "Select timeframe...", 7 => "Last 7 days", 15 => "Last 15 days", 30 => "Last 30 days", 90 => "Last 90 days", 365 => "Last 365 days");
		smarty()->assign('numberOfDays', $numberOfDays);
	}


}