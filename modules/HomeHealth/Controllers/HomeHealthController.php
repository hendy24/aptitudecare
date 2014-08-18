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

		$this->helper = 'PatientMenu';
		
		
		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
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

		$nextWeekSeed = date("Y-m-d", strtotime("+7 days", strtotime($week[0])));

		smarty()->assign(array(
			
		));
		

		smarty()->assign(array(
			'weekSeed' => $weekSeed,
			'weekStart' => date('Y-m-d', strtotime($weekSeed)),
			'week' => $week,
			'advanceWeekSeed' => $nextWeekSeed,
			'retreatWeekSeed' => date("Y-m-d", strtotime("-7 days", strtotime($weekSeed))),
		));

		$_dateStart = $week[0];
		$_dateEnd = $week[6];
		$_location_id = $location->id;
		$_orderby = 'datetime_admit ASC';



		/*	
		 *	Get Admissions for the week
		 */

		$admits = $this->loadModel('HomeHealthSchedule')->fetchAdmits($_dateStart, $_dateEnd, $_location_id);
		//$admits = $homeHealthSchedule->fetchAdmits($_dateStart, $_dateEnd, $_location_id);

		// split the admits up by date
		$admitByDate = array();
		foreach ($week as $day) {
			if (!empty ($admits)) {
				foreach ($admits as $admit) {
					if (strtotime($day) == strtotime(date('Y-m-d', strtotime($admit->datetime_admit)))) {
						$admitsByDate[$day][] = $admit;
					} else {
						$admitsByDate[$day][] = array();
					}
					
				}
			} else {
				$admitsByDate[$day][] = array();
			}
		}
		smarty()->assignByRef('admitsByDate', $admitsByDate);
		

		/*
		 *	Get Discharges for the week
		 */

		$_orderby = 'datetime_discharge ASC';
		$discharges = $this->loadModel('HomeHealthSchedule')->fetchDischarges($_dateStart, $_dateEnd, $_location_id, $_orderby);

		// Split up discharges for each day of the week



		$dischargesByDate = array();
		foreach ($week as $day) {
			if (!empty ($discharges)) {
				foreach ($discharges as $discharge) {
					if (strtotime($day) == strtotime(date('Y-m-d', strtotime($discharge->datetime_discharge)))) {
						$dischargesByDate[$day][] = $discharge;
					} else {
						$dischargesByDate[$day][] = array();
					}
				}
			} else {
				$dischargesByDate[$day][] = array();
			}
			
		}
		smarty()->assignByRef('dischargesByDate', $dischargesByDate);



		// Set page titles
		smarty()->assign('title', 'Home Health Dashboard');	// This is the title that is set in the html head
		smarty()->assign('headerTitle', "{$location->name} Dashboard");  // This is the page title (h1 tag)


	}
	
	
	
}