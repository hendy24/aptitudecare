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
		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->loadModel("Location", auth()->getRecord()->default_location);
		}
		smarty()->assignByRef('location', $location);

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
		$user = auth()->getRecord();

		// check if user has permission to access this page
		

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
		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} else {
			$location = $this->loadModel("Location", auth()->getRecord()->default_location);
		}
		smarty()->assignByRef("location", $location);

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



	public function menu_start_date() {

	}


}