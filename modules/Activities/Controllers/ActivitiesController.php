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
			$this->redirect();
		}

		// fetch activities for the selected location
		$location = $this->getLocation();

		$activities = $this->loadModel('Activity')->fetchActivities($location->id);

		smarty()->assign('title', "Activities");
		
		if (input()->is('post')) {
			
		}

	}





	/* 
	 * Add new activity 
	 *	
	 */
	public function add_new() {
		// fetch activities for the selected location
		$location = $this->getLocation();
	}
	
	public function edit_activity() {
		if (isset (input()->activity_id)) {
			$activity = $this->loadModel('Activity', input()->activity_id);
		} else {
			session()->setFlash("Could not find the activity. Please try again.", 'error');
			$this->redirect(input()->path);
		}
		
		smarty()->assign('activity', $activity);
	}


	public function save_activity() {
		if (input()->location != "") {
			$location = $this->loadModel('Location', input()->location);
		} else {
			session()->setFlash("The activity did not save.  Try again in a few minutes.", 'error');
			$this->redirect(input()->path);
		}		
		
		if (isset (input()->activity_id)) {
			$activity = $this->loadModel('Activity', input()->activity_id);
		} else {
			$activity = $this->loadModel('Activity');
		}
		
		$activity->location_id = $location->id;
		
		if (input()->description != "") {
			$activity->description = input()->description;
		} else {
			$error_messages[] = "Enter an activity description";
		}

		if (input()->datetime_start != "") {
			$activity->datetime_start = mysql_date(input()->datetimestart);
		} else {
			$error_messages[] = "Select a start date";
		}
		
		
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}
		
		if ($activity->save()) {
			session()->setFlash("Activity created successfully!", 'success');
			$this->redirect(array('module' => 'Activities'));

		} else {
			session()->setFlash("The activity did not save.  Try again in a few minutes.", 'error');
			$this->redirect(input()->path);			
		}
		
		
	}



} // END classActivitiesController extends MainPageController 