<?php

class PublicController extends MainPageController {

	public $page = 'public';
	public $template = 'website';
	public $allow_access = true;
	public $title = null;

	public function index() {
		smarty()->assign('title', 'Assisted Living in Anchorage Alaska');
	}

	public function living_at_aspen_creek() {

	}

	public function leadership_team() {

	}

	public function care_team() {

	}

	public function contact() {

	}

	public function resident_application() {

	}

	public function faq() {

	}

	public function error() {
		
	}

	public function careers() {
		
	}

	public function activities() {
		// number of days to show for activities
		$numDays = 5;
		// the number of days count should be 1 less than the number of days to display
		$numDaysCount = $numDays - 1;

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d', strtotime("{$start_date} + {$numDaysCount}"));

		$location = $this->loadModel('Location', 26);
		$activities = $this->loadModel('Activity')->fetchActivities($location->id, $start_date, $numDaysCount);

		smarty()->assign('startDate', $start_date);
		smarty()->assign('activities', $activities);
	}

	public function menu() {

		smarty()->assign('title', "Current Menu");

		// set the number of days to display
		$numDays = 5;
		$numDaysCount = $numDays - 1;

		$week = Calendar::getNextDays($numDays);

		$_dateStart = date('Y-m-d', strtotime($week[0]));
		$_dateEnd = date('Y-m-d', strtotime($week[$numDaysCount]));

		if (strtotime($_dateStart) > strtotime('now')) {
			$today = date('Y-m-d', strtotime('now'));
		} else {
			$today = false;
		}

		smarty()->assign('startDate', $_dateStart);

		// Aspen Creek is location id 26
		$location = $this->loadModel("Location", 26);

		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		$endDay = $startDay + $numDaysCount;


		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateEnd, $startDay, $endDay, $menu->menu_id);
		$this->normalizeMenuItems($menuItems);

		// get alternate menu items
		$alternates = $this->loadModel('Alternate')->fetchAlternates($location->id);
		smarty()->assign('alternates', $alternates);

		smarty()->assign('count', 0);

	}

}