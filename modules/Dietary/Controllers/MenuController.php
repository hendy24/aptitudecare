<?php

class MenuController extends DietaryController {

	// protected $template = "dietary";
	public $module = "Dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';


	public function edit() {
		smarty()->assign('title', "Edit Menu");
		$menuMod = false;

		if (input()->id == "") {
			session()->setFlash("Could not find the menu item you were looking for.", 'error');
			$this->redirect();
		}

		if (isset (input()->date)) {
			$date = input()->date;
		} else {
			$date = null;
		}

		if ($date == null) {
			$corpEdit = true;
			smarty()->assign('corporateEdit', $corpEdit);
		} else {
			$corpEdit = false;
			smarty()->assign('corporateEdit', $corpEdit);
		}

		// Need to fetch the menu item
		if (input()->type == "MenuMod") {
			// fetch the menu modification
			$menuItem = $this->loadModel("MenuMod", input()->id);
			$menuMod = true;

		} elseif (input()->type == "MenuChange") {
			// fetch the changed menu
			$menuItem = $this->loadModel("MenuChange", input()->id);
		} else {
			// fetch the menu item
			$menuItem = $this->loadModel("MenuItem", input()->id);
		}

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);

			// if location is set and corporateEdit is true, then we are on the edit facility
			// page. We need to get a list of all the facilities
			if ($corpEdit) {
				$allLocations = $this->loadModel('Location')->fetchFacilities();
			} else {
				$allLocations = false;
			}
		} else {
			$location = false;
		}

		if ($corpEdit) {
			$allLocations = $this->loadModel('Location')->fetchFacilities();
		} else {
			$allLocations = false;
		}

		smarty()->assign('location', $location);
		smarty()->assign('allLocations', $allLocations);
		smarty()->assign('menuItem', $menuItem);
		smarty()->assign('menuType', input()->type);
		smarty()->assign('date', $date);
		smarty()->assign('menuMod', $menuMod);
	}


	public function submit_edit() {
		// If this is already a menu mod load the current changes...
		if (input()->menu_type == "MenuMod") {
			$menuItem = $this->loadModel('MenuMod', input()->public_id);
		} else {
			$menuItem = $this->loadModel('MenuMod');
		}


		// get the location
		if (input()->location == "") {
			session()->setFlash("No facility menu was selected. Please try again.", 'error');
			$this->redirect();
		} else {
			$location = $this->loadModel('Location', input()->location);
		}

		// if reset is not empty then delete the menu mod item
		if (isset (input()->reset)) {
			if ($menuItem->delete()) {
				session()->setFlash("The menu changes have been deleted and the menu has been reset to the original menu items.", 'success');
				$this->redirect(array('module' => 'Dietary', 'page' => 'info', 'action' => 'current', 'location' => $location->public_id));
			} else {
				session()->setFlash("Could not reset the menu changes. Please try again", 'error');
				$this->redirect(input()->path);
			}
		}


		// get the original menu item
		if (input()->menu_type == "MenuChange") {
			$menuChange = true;
			$origMenuItem = $this->loadModel('MenuChange', input()->public_id);
		} else {
			$menuChange = false;
			$origMenuItem = $this->loadModel('MenuItem', input()->public_id);
		}


		// if there was no reason for a menu change entered throw an error
		if (input()->reason == "") {
			session()->setFlash("You must enter the reason for the menu change.", 'error');
			$this->redirect(input()->path);
		} else {
			$menuItem->reason = input()->reason;
		}

		// set the menu item id
		if ($menuChange) {
			$menuItem->menu_item_id = $origMenuItem->menu_item_id;
		} else {
			$menuItem->menu_item_id = $origMenuItem->id;
		}

		// set the location
		$menuItem->location_id = $location->id;

		// set the date
		$menuItem->date = input()->date;

		// set the menu content to be saved...
		$menuItem->content = input()->menu_content;

		// set the user info who made the change
		$menuItem->user_id = auth()->getRecord()->id;

		if ($menuItem->save()) {
			session()->setFlash("The menu for " . display_date(input()->date) . " has been saved.", 'success');
			$this->redirect(array('module' => 'Dietary', 'page' => 'info', 'action' => 'current', 'location' => $location->public_id));
		} else {
			session()->setFlash("Could not save the menu information. Please try again.", 'error');
			$this->redirect(input()->path);
		}


	}


	/*
	 * -------------------------------------------------------------------------
	 *  EDIT MENU FROM THE CORPORATE MENU PAGE
	 * -------------------------------------------------------------------------
	 *
	 * This will allow editing of the corporate menu for all facilities or for
	 * selected locations.
	 *
	 */
	public function edit_corporate_menu() {
		smarty()->assign('title', "Edit Menu");
		$menuMod = false;
		$menuChange = false;

		if (input()->id == "") {
			session()->setFlash("Could not find the menu item you were looking for.", 'error');
			$this->redirect();
		}

		if (input()->menu != "") {
			$menu = $this->loadModel('Menu', input()->menu);
		} else {
			$menu = $this->loadModel('Menu');
		}

		if (input()->page_count != "") {
			$page_count = input()->page_count;
		} else {
			$page_count = null;
		}

		smarty()->assign('page_count', $page_count);

		// Need to fetch the menu item
		if (input()->type == "MenuMod") {
			// fetch the menu modification
			$menuItem = $this->loadModel("MenuMod", input()->id);
			$menuMod = true;

		} elseif (input()->type == "MenuChange") {
			// fetch the changed menu
			$menuItem = $this->loadModel("MenuChange", input()->id);
			$menuChange = true;
		} else {
			// fetch the menu item
			$menuItem = $this->loadModel("MenuItem", input()->id);
		}

		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location');
		}

		$allLocations = $this->loadModel('Location')->fetchFacilities();

		smarty()->assign('allLocations', $allLocations);
		smarty()->assign('location', $location);
		smarty()->assign('menuItem', $menuItem);
		smarty()->assign('menuChange', $menuChange);
		smarty()->assign('menuType', input()->type);
		smarty()->assign('menuMod', $menuMod);
		smarty()->assign('menu', $menu);

		if (input()->is('post')) {

			// determine if this edit is for all locations or 	only those selected
			if (input()->edit_type == "corp_menu") {
				// this edit is for all locations
				// set the menu item variables
				$menuItem->content = $this->validateMenuContent();

				if ($menuItem->save()) {
					session()->setFlash("The menu was successfully changed for all locations.", 'success');
					$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "corporate_menus", 'menu' => $menu->public_id, 'page_count' => input()->page_count));
				} else {
					session()->setFlash("Could not save the menu. Please try again.", 'error');
					$this->redirect(input()->path);
				}


			} elseif (input()->edit_type == "select_locations") {
				// this edit is only for those locations selected

				// verify that location(s) were selected
				$location = array();
				foreach (input() as $key => $test) {
					$facility = preg_replace("/\d+$/", "", $key);
					if ($facility == "facility") {
						$location[] = $test;
					}
				}

				// if selected locations is false then throw and error and redirect.
				if (empty ($location)) {
					session()->setFlash("You must select the location(s) for which you want to make the change", 'error');
					$this->redirect(input()->path);
				}

				$flash_message = array();
				foreach ($location as $l) {
					$location = $this->loadModel('Location', $l);
					// load the menuChange model and make sure there is not an existing menuChange item
					$menuChange = $this->loadModel('MenuChange')->fetchExisting($menuItem->id, $location->id);
					$menuChange->content = $this->validateMenuContent();
					$menuChange->menu_item_id = $menuItem->id;
					$menuChange->location_id = $location->id;
					$menuChange->user_id = auth()->getRecord()->id;

					if ($menuChange->save()) {
						$flash_message[]['success'] = "The menu was changed for {$location->name}";
					} else {
						$flash_message[]['error'] = "Could not change the menu for {$location->name}";
					}
				}

				foreach ($flash_message as $message) {
					foreach ($message as $k => $m) {
						if ($k == "success") {
							session()->setFlash($m, 'success');
						} elseif ($k == "error") {
							session()->setFlash($m, 'error');
						}
					}
				}

				$this->corpPageRedirect(input()->menu, $location->public_id);

			}
		}

	}


	public function meal_order_form() {

		$this->template = "pdf";

		smarty()->assign('title', "Meal Order Form");

		// Get the selected facility. If no facility has been selected return the users' default location

		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->getLocation();
		}

		// get the correct time for the selected location
		date_default_timezone_set($location->timezone);


		if (isset (input()->start_date)) {
			$start_date = date("Y-m-d", strtotime(input()->start_date));
		} else {
			$start_date = date("Y-m-d", strtotime("now"));
		}

		if (isset (input()->end_date)) {
			$end_date = date("Y-m-d", strtotime(input()->end_date));
		} elseif (isset (input()->start_date)) {
			$end_date = $start_date;
		} else {
			$end_date = date("Y-m-d", strtotime("now"));
		}

		smarty()->assign('startDate', $start_date);

		$urlDate = date('m/d/Y', strtotime($start_date));
		$printDate = date("l, F j, Y", strtotime($start_date));
		smarty()->assign('urlDate', $urlDate);


		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $start_date);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $start_date) % $numDays->count + 1);

		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $start_date, $end_date, $startDay, $startDay, $menu->menu_id);
		$this->normalizeMenuItems($menuItems);

		smarty()->assignByRef('menuItems', $menuItems);
		smarty()->assign('location', $location);

		// get alternates
		$alternates = $this->loadModel("Alternate")->fetchOne(null, array("location_id" => $location->id));

		// put alternates in an array to display similar to the meal menu
		if (strstr($alternates->content, ", ") || strstr($alternates->content, '; ')) {
			$alternatesArray = preg_split("^[,;]^", $alternates->content);
		}

		smarty()->assignByRef("alternates", $alternatesArray);
	}



	public function print_menu() {
		$this->template = "pdf";
		smarty()->assign('title', "Current Menu");

		// Get the selected facility. If no facility has been selected return the users' default location
		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->getLocation();
		}

		smarty()->assign('location', $location);

		// get the correct time for the selected location
		date_default_timezone_set($location->timezone);

		// Check url for week in the past or future
		if (isset (input()->weekSeed)) {
			$weekSeed = input()->weekSeed;
		// If no date is set in the url then default to this week
		} else {
			$weekSeed = date('Y-m-d');
		}


		$week = Calendar::getWeek($weekSeed);

		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));


		smarty()->assign(array(
			'weekSeed' => $weekSeed,
			'weekStart' => date('Y-m-d', strtotime($weekSeed)),
			'week' => $week,
			'advanceWeekSeed' => $nextWeekSeed,
			'retreatWeekSeed' => date("Y-m-d", strtotime("-7 days", strtotime($weekSeed))),
		));

		$_dateStart = date('Y-m-d', strtotime($week[0]));
		$_dateEnd = date('Y-m-d', strtotime($week[6]));

		if (strtotime($_dateStart) > strtotime('now')) {
			$today = date('Y-m-d', strtotime('now'));
		} else {
			$today = false;
		}

		smarty()->assign('today', $today);

		smarty()->assign('startDate', $_dateStart);
		smarty()->assign('endDate', $_dateEnd);


		$urlDate = date('m/d/Y', strtotime($_dateStart));
		$printDate = date("l, F j, Y", strtotime($_dateStart));
		smarty()->assign('urlDate', $urlDate);


		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		$endDay = $startDay + 6;


		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateEnd, $startDay, $endDay, $menu->menu_id);

		$menuItemsArray = array();
		$day = date("m/d/y", strtotime($_dateStart));
		$i = 0;
		foreach ($menuItems as $item) {
			if ($i == 3) {
				$i = 0;
				$day = date("m/d/y", strtotime($day . " + 1 day"));
			}

			if ($i <= 2) {
				$menuItemsArray[$day][] = explode("\n", $item->content);
			}

			$i++;
		}

		smarty()->assign('menuItems', $menuItemsArray);

		// fetch alternate menu items
		$alternates = $this->loadModel('Alternate')->fetchAlternates($location->id);
		smarty()->assign('alternates', $alternates);
		// $this->normalizeMenuItems($menuItems);

		$beverages = $this->loadModel('LocationBeverage')->fetchBeverages($location->id);
		smarty()->assign('beverages', $beverages);

		// fetch NSD name
		$nsd = $this->loadModel('User')->fetchNSD($location->id);
		smarty()->assign('nsd', $nsd);

	}




	/*
	 * -------------------------------------------------------------------------
	 *  DELETE MENU CHANGES
	 * -------------------------------------------------------------------------
	 *
	 * We are going to delete any menu modifications or changes and revert back
	 * to the original menu item
	 *
	 */
	public function delete_item() {
		if (input()->id != "") {
			$changed_menu_item = $this->loadModel(input()->type, input()->id);
			if ($changed_menu_item->delete()) {
				$this->corpPageRedirect();
			}
		}

		$this->redirect();
	}


	private function validateMenuContent() {
		// change the menu content to the newly entered info
		if (input()->menu_content == "") {
			session()->setFlash("Enter the new menu content", 'error');
			$this->redirect(input()->path);
		} else {
			return trim(input()->menu_content);
		}
	}


	private function corpPageRedirect($menu_id = null, $location_id = null) {
		// if the location is set we came from the facility menu page, redirect there
		if (isset (input()->location) && input()->location != null) {
			$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "facility_menus", 'location' => input()->location, 'menu' => input()->menu, 'page_count' => input()->page_count));
		} elseif ($location_id !== null) {
			$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "facility_menus", 'location' => $location_id, 'menu' => input()->menu, 'page_count' => input()->page_count));
		} else {
			// redirect to the corporate menu page
			$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "corporate_menus", 'menu' => input()->menu, 'page_count' => input()->page_count));
		}

	}


}
