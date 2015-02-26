<?php

class DietaryController extends MainPageController {

	// protected $template = "dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';

	public function index() {
		smarty()->assign("title", "Dietary");
		// if user is not authorized to access this page, then re-direct
		if (auth()->getRecord()) {

		}


	}


	public function current() {

		smarty()->assign('title', "Current Menu");
		if (isset(input()->date)) {
			$startDate = date("Y-m-d", strtotime(input()->date));
		} else {
			$startDate = date("Y-m-d", strtotime("last sunday"));
		}
		$endDate = date("Y-m-d", strtotime("$startDate + 6 days"));
		smarty()->assign('startDate', $startDate);
		smarty()->assign('previousWeek', date('m/d/Y', strtotime("$startDate - 7 days")));
		smarty()->assign('nextWeek', date('m/d/Y', strtotime("$startDate + 7 days")));
		$urlDate = date('m/d/Y', strtotime($startDate));
		$printDate = date("l, F j, Y", strtotime($startDate));
		smarty()->assign('urlDate', $urlDate);


		// Get the selected facility. If no facility has been selected return the users' default location
		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->loadModel("Location", auth()->getRecord()->default_location);
		}
		smarty()->assignByRef('location', $location);

		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $startDate);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $startDate) % $numDays->count + 1);
		$endDay = $startDay + 6;


		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $startDate, $endDate, $startDay, $endDay, $menu->menu_id);

		foreach ($menuItems as $key => $item) {
			if ($item->date != "") {
				$menuItems[$key]->type = "MenuMod";
			} elseif ($item->menu_item_id != "") {
				$menuItems[$key]->type = "MenuChange";
			} else {
				$menuItems[$key]->type = "MenuItem";
			}

			// Get the current week
			$menuWeek = floor($item->day / 7);

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


	public function corporate_menus() {

	}


	public function facility_menus() {
		smarty()->assign('title', "Facility Menu");
		$user = auth()->getRecord();

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location', $user->default_location);
		}

		$date = date('Y-m-d', strtotime("now"));
		$currentMenu = $this->loadModel('LocationMenu')->fetchMenu($location->id, $date);

		if (isset (input()->menu_id)) {
			$selectedMenu = $this->loadModel('Menu', input()->menu_id);
		} else {
			$selectedMenu = $currentMenu;
		}

		smarty()->assign('location', $location);
		smarty()->assign('currentMenu', $currentMenu);

		// get all available menus for this location
		$availableMenus = $this->loadModel('LocationMenu')->fetchAvailable($location->id);
		smarty()->assign('availableMenus', $availableMenus);
		smarty()->assign('selectedMenu', $selectedMenu);

		// paginate menu info
		$results = $this->paginate($currentMenu, $location);


	}


	public function alt_menu_items() {

	}


	public function menu_changes() {

	}


	public function meal_times() {

	}


	public function welcome_info() {

	}


	public function menu_start_date() {

	}


}