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

	}





	/* 
	 * Add new activity 
	 *	
	 */
	public function add_new() {
		// fetch activities for the selected location
		$location = $this->getLocation();
	}





} // END classActivitiesController extends MainPageController 