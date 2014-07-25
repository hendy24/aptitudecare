<?php

class HomeHealthController extends MainPageController {


	/*
	 * -------------------------------------------
	 * HOME HEALTH INDEX PAGE
	 * -------------------------------------------
	 *
	 *	This is the main home health landing page.  It will be similar to the facility index page
	 * 	in the admission app (module).  We will show all the approved and pending admits as well 
	 * 	as the discharges for the week at each location.
	 * 
	 */
	 
	public function index() {
		
		$lm = $this->loadModel('Location');
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $lm->fetchLocation(input()->location);
		} else {
			// Get the users default location from the session
			$location = $lm->fetchLocation(auth()->getDefaultLocation());
		}

		if (!isset($location)) {
			session()->setFlash('Cannot access the information for the selected location.', 'error');
			$this->redirect();
		}

		smarty()->assignByRef('location', $location);
		
		// Probably need to do some type of user authorizated access check here

		// Check url for week in the past or future
		if (isset (input()->weekSeed)) {
			$weekSeed = input()->weekSeed;
		
		// If no date is set in the url then default to this week
		} else {
			$weekSeed = date('Y-m-d');
		}
		$week = Calendar::getWeek($weekSeed);

		// Now get the next week
		$nextWeekSeed = date('Y-m-d', strtotime('+7 days', strtotime($week[0])));

		smarty()->assign(array(
			'weekSeed' => $weekSeed,
			'nextWeekSeed' => $nextWeekSeed,
			'weekStart' => date('Y-m-d', strtotime($weekSeed)),
			'week' => $week
		));

		$_dateStart = $week[0];
		$_dateEnd = $week[6];
		$_location_id = $location->id;
		$_orderby = 'datetime_admit ASC';



		/*	
		 *	Get Admissions for the week
		 */

		$homeHealthSchedule = $this->loadModel('HomeHealthSchedule');
		$admits = $homeHealthSchedule->fetchAdmits($_dateStart, $_dateEnd, $_location_id);

		smarty()->assignByRef('admits', $admits);

		// Split admits up by date
		$admitsByDate = array();
		foreach ($admits as $admit) {
			$date = date('Y-m-d', strtotime($admit->datetime_admit));
			if (! isset ($admitsByDate[$date])) {
				$admitsByDate[$date] = array(); 
			}
			$admitsByDate[$date][] = $admit;
		}
		smarty()->assignByRef('admitsByDate', $admitsByDate);


		/*
		 *	Get Discharges for the week
		 */

		$_orderby = 'datetime_discharge ASC';
		$discharges = $homeHealthSchedule->fetchDischarges($_dateStart, $_dateEnd, $_location_id, $_orderby);

		// Split up discharges for each day of the week
		$dischargesByDate = array();
		foreach ($discharges as $discharge) {
			$date = date('Y-m-d', strtotime($discharge->datetime_discharge));
			if (! isset ($dischargesByDate[$date])) {
				$dischargesByDate[$date] = array();
			}
			$dischargesByDate[$date][] = $discharge;
		}
		smarty()->assignByRef('dischargesByDate', $dischargesByDate);


		// Set page titles
		smarty()->assign('title', 'Home Health Dashboard');	// This is the title that is set in the html head
		smarty()->assign('headerTitle', "{$location->name} Dashboard");  // This is the page title (h1 tag)


	}
	
	
	
}