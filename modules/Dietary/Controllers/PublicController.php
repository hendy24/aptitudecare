<?php

class PublicController extends DietaryController {

	public $template = "public";
	public $allow_access = array('index', 'no_access');




/*
 * -----------------------------------------------------------------------------
 * PUBLIC PAGE MAIN page
 * -----------------------------------------------------------------------------
 * This page displays the menu and activities indended to be accessed from an
 * internet connected TV in the faciliity dining room. Access to this page is
 * restricted to logged in users or by IP address. The IP addresses for
 * facilities are located in the ac_ip_address db table.
 *
 */
	public function index() {
		// need to allow access to this page when user is not logged it.
		$user = auth()->getRecord();

		// get the user agent
		$ua = $_SERVER['HTTP_USER_AGENT'];

		// array of user agents for which we want to throw the warning
		// these will be the Google TV's located in the dining rooms trying to access without the Raspberry Pi
		$ua_array = array(
			// 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36',
			'Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.77 Large Screen Safari/534.24 GoogleTV/092754',
			'Mozilla/5.0 (X11; Linux armv7l) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.84 Safari/537.36',
		);
		// set a warning variable to false be default
		$warning = false;

		// if the user agent is in the array then set the warning variable to true
		// this means the TV is trying to access the site without using the Raspberry Pi
		if (in_array($ua, $ua_array)) {
			$warning = true;
		}

		// get the location
		if (isset (input()->location)) {
			$location = $this->loadModel("Location", input()->location);
		} elseif (!empty ($user)) {
			// get the current users default location
			$location = $this->loadModel("Location", $user->default_location);
		} else {
			// check access to the page based on the IP address
			$current_ip = $_SERVER['REMOTE_ADDR'];
			$ip_address = $this->loadModel('IpAddress')->fetchByIp($current_ip);
			if (empty($ip_address)) {
				$this->redirect(array("module" => "Dietary", "page" => "public", "action" => "no_access"));
			} else {
				$location = $this->loadModel("Location", $ip_address->location_id);
			}

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
		} else {
			$end_date = date("Y-m-d", strtotime("now"));
		}

		smarty()->assign('startDate', $start_date);

		$urlDate = date('m/d/Y', strtotime($start_date));
		$printDate = date("l, F j, Y", strtotime($start_date));
		smarty()->assign('urlDate', $urlDate);

		// Get the menu id the facility is currently using
		$menu = $this->loadModel("Menu")->fetchMenu($location->id, $start_date);

		// Get the meal times
		$meal = $this->loadModel("Meal")->fetchByLocation($location->id);
		// Get the public greeting about meals
		$locationDetail = $this->loadModel("LocationDetail")->fetchOneByLocation($location->id);

		// Get the menu day for today
		$numDays = $this->loadModel("MenuItem")->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $start_date) % $numDays->count + 1);

		// Get the menu items for the week
		$menuItems = $this->loadModel("MenuItem")->fetchMenuItems($location->id, $start_date, $end_date, $startDay, $startDay, $menu->menu_id);
		$this->normalizeMenuItems($menuItems);

		// get alternates
		$alternates = $this->loadModel("Alternate")->fetchOne(null, array("location_id" => $location->id));

		smarty()->assign('menu', $menu);
		smarty()->assign('warning', $warning);
		smarty()->assign('meal', $meal);
		smarty()->assignByRef('menuItems', $menuItems);
		smarty()->assignByRef("alternates", $alternates);
		smarty()->assignByRef('location', $location);
		smarty()->assign('locationDetail', $locationDetail);

		// Fetch the activities for the date range
		$activities = $this->loadModel('Activity')->fetchActivities($location->id, $start_date, 4);
		smarty()->assignByRef('weekActivities', $activities);

		// $headless = false;
		// header("Cache-Control; no-cache; must-revalidate");
		// header("Expires: Fri, 31 Jul 1980 06:00:00 GMT");

		// if (isset(input()->headless) && input()->headless == true) {
		// 	$headless = true;
		// } 

		// smarty()->assign('headless', $headless);

	}



/*
 * -----------------------------------------------------------------------------
 * NO ACCESS ERROR PAGE
 * -----------------------------------------------------------------------------
 * Unless a user trying to access the menu page is logged in or is accessing the
 * page from within a facility they will be re-directed to this error page.
 *
 */
	public function no_access() {

	}


}
