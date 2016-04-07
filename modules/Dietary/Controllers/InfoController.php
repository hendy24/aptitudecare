<?php

class InfoController extends DietaryController {

	public $module = "Dietary";
	protected $navigation = 'dietary';
	protected $searchBar = 'dietary';
	protected $helper = 'DietaryMenu';





	public function current() {

		smarty()->assign('title', "Current Menu");

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


		$urlDate = date('m/d/Y', strtotime($_dateStart));
		$printDate = date("l, F j, Y", strtotime($_dateStart));
		smarty()->assign('urlDate', $urlDate);


		// Get the selected facility. If no facility has been selected return the users' default location
		$location = $this->getLocation();

		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		$endDay = $startDay + 6;


		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateEnd, $startDay, $endDay, $menu->menu_id);
		$this->normalizeMenuItems($menuItems);
	}

	public function create(){

	}

	public function save_create() {

		if (input()->menu_name != "") {
			$new_menu = $this->loadModel('Menu');
			$new_menu->name = input()->menu_name;
		} else {
			$error_messages[] = "Enter a name for the new menu";
			
		}

		if (input()->num_weeks != "") {
			$num_days = input()->num_weeks * 7;
		} else {
			$error_messages[] = "Enter the number of weeks in the menu";
		}

		// break point
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->current_url);
		}

		if ($new_menu->save()) {
			$success = false;
			for ($day = 1; $day <= $num_days; $day++) {
				while ($meal_id <= 3) {
					$menu_item = $this->loadModel('MenuItem');
					$menu_item->menu_id = $new_menu->id;
					$menu_item->meal_id = $meal_id;
					$menu_item->day = $day;
					$menu_item->content = "No menu content has been entered.";
					$menu_item->save();
					$meal_id++;
				}
				$meal_id = 1;
				$success = true;
			}

			if ($success) {
				session()->setFlash("The new menu was created", 'success');
				$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "corporate_menus", 'menu' => $new_menu->public_id));
			} else {
				session()->setFlash("Could not create the new menu. Please try again.", 'error');
				$this->redirect(input()->current_url);
			}
		}

	}


	/*
	 * -------------------------------------------------------------------------
	 *  Manage the corporate menus
	 * -------------------------------------------------------------------------
	 *
	 * Access to this page is restricted to corporate admins
	 *
	 */
	public function manage() {
		// if the user does not have permission then throw error and redirect
		if (!auth()->hasPermission('create_menu')) {
			session()->setFlash("You do not have permission to access that page.", 'error');
			$this->redirect(array('module' => $this->getModule()));
		}

		// fetch all the menus
		$menus = $this->loadModel('Menu')->fetchAll();

		smarty()->assign('menus', $menus);

	}


	/*
	 * -------------------------------------------------------------------------
	 *  DELETE USERS
	 * -------------------------------------------------------------------------
	 */
	public function delete_menu() {
		//	If the id var is filled then delete the item with that id
		if (input()->menu != '') {
			$menu = $this->loadModel('Menu', input()->menu);


			// delete all entries in menu_item
			if ($this->loadModel('MenuItem')->deleteMenuItems($menu->id)) {
				if ($menu->delete()) {
					return true;
				}

				return false;
			}

			return false;
		}

		return false;
	}


	/*
	 * -------------------------------------------------------------------------
	 *  EDIT MENU
	 * -------------------------------------------------------------------------
	 *
	 * Functionality to change the menu name only right now. May need to add 
	 * addtional functionality in the future...
	 *
	 */
	public function edit_menu() {
		$menu = $this->loadModel('Menu', input()->menu);
		smarty()->assign('menu', $menu);

		// if this is a post then we are trying to save
		if (input()->is('post')) {
			$menu->name = input()->name;

			if ($menu->save()) {
				session()->setFlash("The name of the menu has been changed.", 'success');
				$this->redirect(array('module' => "Dietary", 'page' => "info", 'action' => "manage"));
			} else {
				session()->setFlash("Could not change the name of the menu. Please try again.", 'error');
				$this->redirect(input()->current_url);
			}
		}
	}



	public function corporate_menus() {
		smarty()->assign('title', "Corporate Menus");

		// Get all available menus
		$menus = $this->loadModel('Menu')->fetchAll();
		smarty()->assign('menus', $menus);

		// Set the selected menu info
		if (isset (input()->menu)) {
			$selectedMenu = $this->loadModel('Menu', input()->menu);
		} else {
			// if no menu has been selected we will just assign the first menu in the array
			$selectedMenu = $menus[0];
		}
		smarty()->assign('selectedMenu', $selectedMenu);

		if (isset (input()->page)) {
			$page = input()->page;
		} else {
			$page = 1;
		}


		// Fetch content for selected menu
		$menuItems = $this->loadModel('MenuItem')->paginateMenuItems($selectedMenu->id, null, $page);
		$this->normalizeMenuItems($menuItems);

	}


	public function facility_menus() {
		smarty()->assign('title', "Facility Menu");
		$location = $this->getLocation();

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

		if (isset (input()->page)) {
			$page = input()->page;
		} else {
			$page = false;
		}

		// paginate menu info
		$results = $this->loadModel('MenuItem')->paginateMenuItems($currentMenu->menu_id, $location->id, $page);
		$this->normalizeMenuItems($results);

		smarty()->assign('menu', $selectedMenu);

	}


	public function public_page_items() {
		smarty()->assign("title", "Public Page Items");
		$location = $this->getLocation();

		// menu greeting
		$menuGreeting = $this->loadModel("LocationDetail")->fetchOne(null, array("location_id" => $location->id));
		smarty()->assign("menuGreeting", $menuGreeting);


		// meal time info
		$meals = $this->loadModel("Meal")->fetchAll(null, array("location_id" => $location->id));
		smarty()->assign("meals", $meals);


		// alternate menu items
		$alternates = $this->loadModel("Alternate")->fetchOne(null, array("location_id" => $location->id));
		smarty()->assignByRef("alternates", $alternates);




	}



	public function submitWelcomeInfo() {
		$greeting = $this->loadModel("LocationDetail", input()->location_detail_id);
		$location = $this->loadModel("Location", input()->location);
		$greeting->menu_greeting = input()->menu_greeting;
		if ($greeting->save()) {
			session()->setFlash("The menu greeting info was changed for {$location->name}", "success");
		} else {
			session()->setFlash("Could not save the greeting info. Please try again.", "error");
		}

		$this->redirect(input()->path);
	}


	public function submitMealTimes() {

		$message = array();


		$end_time = get_object_vars(input()->end);

		foreach (input()->start as $key => $start_time) {
			$meal = $this->loadModel("Meal", $key);
			if ($start_time != "") {
				$meal->start = date("H:i:s", strtotime($start_time));
			} else {
				session()->setFlash("Set a meal time and try again.", "error");
				$this->redirect(input()->path);
			}
			if ($end_time  != "") {
				$meal->end = date("H:i:s", strtotime($end_time[$key]));
			} else {
				session()->setFlash("Set a meal time and try again.", "error");
				$this->redirect(input()->path);
			}

			if ($meal->save()) {
				$message[] = "The meal time was successfully saved.";
			} else {
				$message[] = "Could not save the meal time.";
			}
		}

		session()->setFlash($message, "success");
		$this->redirect(input()->path);

	}


	public function submitAltItems() {
		$location = $this->loadModel('Location', input()->location);
		$alternate = $this->loadModel('Alternate', input()->alt_menu_id);

		if (input()->alt_menu == "") {
			session()->setFlash("Please enter items for the alternate menu", 'error');
			$this->redirect(input()->path());
		} else {
			$alternate->content = input()->alt_menu;
		}

		if ($alternate->location_id == "") {
			$alternate->location_id = $location->id;
		}

		$alternate->user_id = auth()->getRecord()->id;

		if ($alternate->save()) {
			session()->setFlash("The alternate menu was changed for {$location->name}", 'success');
		} else {
			session()->setFlash("Could not save the alternate menu changes. Please try again.", 'error');
		}
		$this->redirect(input()->path);
	}



/*
 * SET MENU START DATE
 * This page is used to set the start date when changing the menu the facility will
 * be using. This change usually only occurs twice per years, but depends on how
 * many menus the facility will utilyze throughout the year.
 *
 */
	public function menu_start_date() {
		smarty()->assign("title", "Menu Start Date");
		$location = $this->getLocation();

		$date = date("Y-m-d", strtotime("now"));
		smarty()->assign("date", $date);
		$availableMenus = $this->loadModel("LocationMenu")->fetchAvailable($location->id);
		$currentMenu = $this->loadModel("LocationMenu")->fetchCurrent($location->id, $date);
		smarty()->assignByRef("availableMenus", $availableMenus);
		smarty()->assignByRef("currentMenu", $currentMenu);
	}



	/*
	 * SUBMIT THE START DATE
	 * Submits, checks for errors, and saves the start date submitted from the set
	 * set menu start date page.
	 *
	 */
	public function submitStartDate() {
		$location = $this->loadModel("Location", input()->location);
		if (input()->menu != "") {
			$menu = $this->loadModel("Menu", input()->menu);
			$locationMenu = $this->loadModel("LocationMenu")->checkExisting($menu->id, $location->id);
		} else {
			session()->setFlash("Please select a new menu to start", "error");
			$this->redirect(input()->path);
		}

		if (input()->date_start != "") {
			$locationMenu->date_start = date("Y-m-d", strtotime(input()->date_start));
			$date = input()->date_start;
		} else {
			session()->setFlash("Select the date to start the menu.", "error");
			$this->redirect(input()->path);
		}

		if ($locationMenu->save()) {
			session()->setFlash("The new menu will start on {$date} for {$location->name}", "success");
			$this->redirect(array("module" => $this->module));
		}


	}

}
