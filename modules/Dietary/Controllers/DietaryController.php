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
		if (isset(input()->location) && input()->location != "") {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location', auth()->getRecord()->default_location);
		}

		if ($location->location_type != 1) {
			$this->redirect();
		}

		// get a list of the current patients from the admission app for the current location
		$currentPatients = $this->loadModel('AdmissionDashboard', false, 'HomeHealth')->fetchCurrentPatients($location->id);
		smarty()->assign('currentPatients', $currentPatients);
	}



	public function normalizeMenuItems($menuItems) {
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


		}

		smarty()->assign('count', 0);
		smarty()->assign('menuWeek', $menuWeek);
		smarty()->assignByRef('menuItems', $menuItems);
	}


}