<?php

class HomeHealthController extends MainPageController {

	public $module = 'HomeHealth';
	
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

		if (isset (input()->is_micro)) {
			$this->template = "blank";
			$isMicro = true;
		} else {
			$isMicro = false;
		}
		smarty()->assign('isMicro', $isMicro);

		$location = false;
		$area = false;
		// location in the url = a home health location
		// area in the url = a facility location

		if (isset(input()->location)) {
			// If the location is set in the url, get the home health location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				$area = $this->loadModel('Location', input()->area);	
			} 

		} else {
			// If no location, the get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());

			//  Check if the default location is home health, if not need to get the associated home health agency
			if ($location->location_type != 2) {
				$location = $location->fetchHomeHealthLocation();
			} 		
		}

		smarty()->assignByRef('selectedArea', $area);
		smarty()->assignByRef('loc', $location);

		// get the areas (facilities) associated with the selected home health agency
		if ($area) {
			$areas = array($area);
		} else {
			$areas = $this->loadModel('Location')->fetchFacilitiesByHomeHealthId($location->id);			
		}

		
		
		
		
		// Probably need to do some type of user authorization access check here

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

		$_dateStart = date('Y-m-d 00:00:01', strtotime($week[0]));
		$_dateEnd = date('Y-m-d 23:59:59', strtotime($week[6]));
		$_orderby = 'datetime_admit ASC';



		/*	
		 *	Get Admissions for the week
		 */

		//	Get admission dashboard discharges as new pending admits
		//	This will be integrated more tightly once the admission dashboard is re-built
		$adDischarges = $this->loadModel('AdmissionDashboard')->fetchDischarges($_dateStart, $_dateEnd, $location->id, $areas);
		$admits = $this->loadModel('HomeHealthSchedule')->fetchAdmits($_dateStart, $_dateEnd, $areas);
		

		// split the admits up by date
		$admitByDate = array();
		foreach ($week as $day) {
			if (!empty ($admits)) {
				foreach ($admits as $admit) {
					if (strtotime($day) == strtotime(date('Y-m-d', strtotime($admit->start_of_care)))) {
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

		$_status = "Discharged";
		$_orderby = 'datetime_discharge ASC';
		//$discharges = $this->loadModel('HomeHealthSchedule')->fetchDischarges($_dateStart, $_dateEnd, $areas, $_status, $_orderby);

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


	}
	
	
	
}