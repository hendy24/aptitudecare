<?php

/**
 * undocumented class
 *
 * @package default
 * @author
 **/
class ActivitiesController extends MainPageController {


	/*
	 * Activity home page
	 *
	 */
	public function index() {
		// make sure the user has permission to access this page		
		if (!auth()->hasPermission('manage_activities')) {
			$module = $this->loadModel('Module', auth()->getRecord()->default_module);
			session()->setFlash("You don't have permission to access that page.", 'error');
			$this->redirect(array('module' => $module->name));
		}

		// set the start and end dates for the week view
		if (isset (input()->date)) {
			$start_date = date("Y-m-d", strtotime(input()->date));
			// check if the day is a Sunday, if not find date for the previous Sunday
			if (date("l", strtotime($start_date)) != "Sunday") {
				$start_date = date("Y-m-d", strtotime($start_date . " previous Sunday"));
			}
		} else {
			$start_date = date("Y-m-d", strtotime("previous Sunday"));
		}
		$end_date = date("Y-m-d", strtotime("{$start_date} + 6 days"));

		// assign dates for last and next week
		$previous_week = date("m/d/Y", strtotime($start_date . "- 7 days"));
		$next_week = date("m/d/Y", strtotime($start_date . "+ 7 days"));



		// fetch activities for the selected location

		$location = $this->getLocation();
		$activities = $this->loadModel('Activity')->fetchActivities($location->id, $start_date);

		//pr($activities); exit;

		smarty()->assignByRef('activitiesArray', $activities);
		smarty()->assign('title', "Activities");

		if (input()->is('post')) {

		}

		smarty()->assign('startDate', $start_date);
		smarty()->assign('endDate', $end_date);
		smarty()->assign('previousWeek', $previous_week);
		smarty()->assign('nextWeek', $next_week);

	}





	/*
	 * Add new activity
	 *
	 */
	public function activity() {
		// fetch activities for the selected location
		$location = $this->getLocation();


		if (input()->type == "edit") {
			smarty()->assign('headerTitle', "Edit Activity");
			if (isset (input()->id) && input()->id != "") {
				$activity = $this->loadModel('Activity', input()->id)->fetchSchedule();
				//Split datetime to date AND time
				$activity->date_start = $activity->date_start;
				$activity->time_start = $activity->date_start;
			}
		} else {
			smarty()->assign('headerTitle', "Add a New Activity");
			$activity = $this->loadModel('Activity');
			if (isset (input()->date) && input()->date != "") {
				$activity->date_start = mysql_date (input()->date);
			} else {
				$activity->date_start = mysql_date();
			}

			// if an empty object is loaded then set null values for the activity_schedule table that was not joined
			$activity->time_start = null;
			$activity->repeat_week = null;
			$activity->repeat_weekday = null;
			$activity->daily = null;
			$activity->all_day = null;

		}
		
		smarty()->assignByRef('activity', $activity);
	}




	public function save_activity() {
		if (input()->activity_id != "") {
			$activity = $this->loadModel('Activity', input()->activity_id);
			$activity_schedule = $this->loadModel('ActivitySchedule')->fetchSchedule($activity->id);
		} else {
			$activity = $this->loadModel('Activity');
			$activity_schedule = $this->loadModel('ActivitySchedule');
		}
		$feedback = array();
		
		// if there is no location we don't know where to create the activity
		if (input()->location == "") {
			session()->setFlash("You must select a facility for this activity.", 'error');
			$this->redirect(array("module" => $this->module));
		}

		$location = $this->loadModel('Location', input()->location);
		$activity->location_id = $location->id;

		if (input()->description != "") {
			$activity->description = input()->description;
		} else {
			$feedback[] = "Enter an activity description";
		}

		if (input()->date_start != "") {
			$activity_schedule->date_start = mysql_date(input()->date_start);
		} else {
			$feedback[] = "Enter a date for the activity";
		}

		if (input()->time_start != "") {
			$activity_schedule->time_start = date("H:i:s", strtotime(input()->time_start));
		}

		if (input()->repeat_type != "") {
			if (input()->repeat_type == "daily") {
				$activity_schedule->daily = true;
			}
			if (input()->repeat_type == "weekly") {
				$activity_schedule->daily = false;
			}
			if (input()->repeat_type == "monthly") {
				//$activity_schedule->repeat_week = ceil(date("j", strtotime(input()->date_start)));
				$activity_schedule->repeat_week = ceil(date("j", strtotime(input()->date_start))/7);
				$activity_schedule->daily = false;
			}

			$activity_schedule->repeat_weekday = date("w", strtotime(input()->date_start));
		}
		else{
			//daily field cannot be null
			$activity_schedule->daily = false;
		}
		//Probably could refactor this to just have input()->allDay be the value
		if (isset (input()->all_day)) {
			if(input()->all_day == "true"){
				$activity_schedule->all_day = 1;
			}
			else{
				$activity_schedule->all_day = 0;
			}	
		}
		pr ($activity_schedule); exit;
		// BREAKPOINT
		if (!empty ($feedback)) {
			session()->setFlash($feedback, 'error');
			$this->redirect(input()->current_url);
		}

		$activity->user_id = auth()->getRecord()->id;
		
		if ($activity->save()) {
			$activity_schedule->activity_id = $activity->id;
			if ($activity_schedule->save()) {
				session()->setFlash("The activity was saved", 'success');
				$this->redirect(array("module" => $this->module));				
			}
			//}
		} else {
			session()->setFlash("Could not save the activity. Please try again", 'error');
			$this->redirect(input()->current_url);
		}
	}


	public function getWeek($date, $rollover = false) {
		$cut = substr($date, 0, 8);
		$daylen = 86400;

		$timestamp = strtotime($date);
		$first = strtotime($cut . "00");
		$elapsed = ($timestamp - $first) / $daylen;

		$weeks = 1;

		for ($i=1; $i<=$elapsed; $i++) {
			$dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
			$dattimestamp = strtotime($dayfind);

			$day = strtolower(date("l", $dattimestamp));

			if ($day == strtolower($rollover)) {
				$weeks++;
			}
		}
		return $weeks;
	}

	//edit_activity is currently not used
	public function edit_activity() {
		if (isset (input()->activity_id)) {
			$activity = $this->loadModel('Activity', input()->activity_id);
		} else {
			session()->setFlash("Could not find the activity. Please try again.", 'error');
			$this->redirect(input()->path);
		}
		smarty()->assign('activity', $activity);
	}


} // END classActivitiesController extends MainPageController
