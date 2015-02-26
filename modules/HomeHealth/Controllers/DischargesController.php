<?php

class DischargesController extends MainPageController {

	public function manage() {

	}

	public function schedule() {
		smarty()->assign('title', "Schedule Discharges");
		

		if (isset(input()->location)) {
			// If the location is set in the url, get the location by the public_id
			$location = $this->loadModel('Location', input()->location);

			if (isset (input()->area)) {
				$area = $this->loadModel('Location', input()->area);
			} else {
				$area = $location->fetchLinkedFacility($location->id);
			}
		} else {
			// Get the users default location from the session
			$location = $this->loadModel('Location', auth()->getDefaultLocation());
			$area = $location->fetchLinkedFacility($location->id);
		}

		smarty()->assign('loc', $location);
		smarty()->assignByRef('selectedArea', $area);


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

		$_dateStart = date('Y-m-d 00:00:01', strtotime($week[0]));
		$_dateEnd = date ('Y-m-d 23:59:59', strtotime($week[6]));
		$_location_id = $area->id;

		// Get all patients currently at the facility for the current week
		$current = $this->loadModel('Patient')->fetchCurrentPatients($area->id);

		/*
		 *	Get Discharges for the week
		 */

		$_status = 'Discharged';
		$_orderby = 'datetime_discharge ASC';
		$discharges = $this->loadModel('HomeHealthSchedule')->fetchDischarges($_dateStart, $_dateEnd, $area->id, $_status, $_orderby);

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


		smarty()->assign('current', $current);
		smarty()->assignByRef('discharged', $dischargesByDate);
		smarty()->assign('week', $week);

	}


	public function save_discharge() {
		$this->template = "blank";
		if (input()->public_id != '') {
			$public_id = input()->public_id;
		} else {
			return false;
		}
			
		if (input()->date != '') {
			$date = input()->date;
		} else {
			return false;
		}	

		echo $public_id;
							
		$schedule = $this->loadModel('HomeHealthSchedule', $public_id);
		$schedule->datetime_discharge = date('Y-m-d 11:00:00', strtotime($date));	
		$schedule->status = "Discharged";
		
		$schedule->save();
		
		return true;
	}


	public function clear_discharge() {
		if (input()->public_id != '') {
			$public_id = input()->public_id;
		} else {
			return false;
		}
		
		$schedule = $this->loadModel('HomeHealthSchedule', $public_id);
		$schedule->datetime_discharge = null;
		$schedule->discharge_to = null;
		$schedule->discharge_location_id = null;
		$schedule->status = 'Approved';
		$schedule->save();
		
		return true;
	}
}