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

		if (isset (input()->isMicro)) {
			$this->template = "blank";
			$isMicro = true;
		} else {
			$isMicro = false;
		}
		smarty()->assign('isMicro', $isMicro);

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


		$location = $this->getLocation();
		$areas = $this->getArea();

		if (isset (input()->area) && input()->area != "all") {
			// get single selected area
			$areas = array($this->getArea());
		} else {
			// get all the areas
			$areas = $this->getAreas();
		}


		/*
		 *	GET ADMISSIONS FOR THE WEEK
		 *	Sync admission dashboard discharges with the ac_*.admit_schedule table and ac_*.patient table
		 *	Remove $adDischarges once the admission app has been re-built in the new framework.
		 */
		$adDischarges = $this->loadModel('AdmissionDashboard')->syncDischarges($_dateStart, $_dateEnd, $location->id, $areas);
		$admits = $this->loadModel('HomeHealthSchedule')->fetchAdmits($_dateStart, $_dateEnd, $areas);

		// split the admits up by date
		$admitByDate = array();
		foreach ($week as $day) {
			if (!empty ($admits)) {
				foreach ($admits as $admit) {
					if (strtotime($day) == strtotime(date('Y-m-d', strtotime($admit->start_of_care)))) {
						$admitsByDate[$day][] = $admit;
					}  elseif (strtotime($day) == strtotime(date('Y-m-d', strtotime($admit->referral_date)))) {
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
		$discharges = $this->loadModel('HomeHealthSchedule')->fetchDischarges($_dateStart, $_dateEnd, $areas, $_status, $_orderby);

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
