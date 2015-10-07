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
			session()->setFlash("You don't have permission to access that page.", 'error');
			$this->redirect(array('module' => auth()->getRecord()->default_module));
		}

		// set the start and end dates for the week view
		if (isset (input()->date)) {
			$start_date = date("Y-m-d", strtotime(input()->date));
		} else {
			$start_date = date("Y-m-d", strtotime("previous Sunday"));
		}
		$end_date = date("Y-m-d", strtotime("{$start_date} + 6 days"));


		// fetch activities for the selected location
		$location = $this->getLocation();
		$activities = $this->loadModel('Activity')->fetchActivities($location->id, $start_date);
		/*pr($location);
		pr($start_date);*/
		//pr($activities); exit;

		smarty()->assignByRef('activitiesArray', $activities);
		smarty()->assign('title', "Activities");

		if (input()->is('post')) {

		}

		smarty()->assign('startDate', $start_date);
		smarty()->assign('endDate', $end_date);
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
				$activity->date_start = $activity->datetime_start;
				$activity->time_start = $activity->datetime_start;
			}
		} else {
			smarty()->assign('headerTitle', "Add a New Activity");
			$activity = $this->loadModel('Activity');
			if (isset (input()->date) && input()->date != "") {
				$activity->date_start = mysql_date (input()->date);
			} else {
				$activity->date_start = mysql_date();
			}

			$activity->time_start = null;
			$activity->repeat_week = null;
			$activity->repeat_weekday = null;
			$activity->daily = null;

		}

		smarty()->assignByRef('activity', $activity);
	}




	public function save_activity() {

		if (isset (input()->activity_id)) {
			$activity = $this->loadModel('Activity', input()->activity_id);
		} else {
			$activity = $this->loadModel('Activity');
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
			$activity->datetime_start = mysql_date(input()->date_start);
		} else {
			$feedback[] = "Enter a date for the activity";
		}

		if (input()->time_start != "") {
			$activity->datetime_start = mysql_datetime(input()->date_start . " " . input()->time_start);
		}

		if (input()->repeat_type != "") {
			if (input()->repeat_type == "daily") {
				$activity->daily = true;
			}
			if (input()->repeat_type == "weekly") {
				$activity->daily = false;
			}
			if (input()->repeat_type == "monthly") {
				$activity->repeat_week = ceil(date("j", strtotime(input()->date_start)));
				$activity->daily = false;
			}

			$activity->repeat_weekday = date("w", strtotime(input()->date_start));
		}
		else{
			//daily field cannot be null
			$activity->daily = false;
		}
		//Probably could refactor this to just have input()->allDay be the value
		if(input()->all_day == "on"){
			$activity->all_day = true;
		}
		else{
			$activity->all_day = false;
		}
		// BREAKPOINT
		if (!empty ($feedback)) {
			session()->setFlash($feedback, 'error');
			$this->redirect(input()->current_url);
		}

		if ($activity->save()) {
				session()->setFlash("The activity was saved", 'success');
				$this->redirect();
			//}
		} else {
			session()->setFlash("Could not save the activity. Please try again", 'error');
			$this->redirect(input()->current_url);
		}


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
