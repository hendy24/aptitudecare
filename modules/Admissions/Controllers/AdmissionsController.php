<?php

class AdmissionsController extends MainPageController {
	
	protected $template = "main";
	public $module = "Admissions";
	public $page = "admissions";
	public $allow_access = false;

	// protected $navigation = 'dietary';
	// protected $searchBar = 'admission';
	// protected $helper = 'AdmissionMenu';



	/* 
	 * Prospects Home Page
	 *
	 */
	public function index() {
		
		smarty()->assign('title', "Current Prospects");

		// get prospects list
		$prospects = $this->loadModel('Prospect')->fetchProspects();
		smarty()->assign('prospects', $prospects);
	}


	/* 
	 * New Prospect
	 *
	 */
	public function new_prospect() {
		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		smarty()->assign('timeframe', $timeframe);
	}



			
	/* 
	 * Resident Profile page
	 *
	 */
	public function profile() {
		if (isset (input()->id)) {
			if (input()->id != null) {
				$prospect = $this->loadModel('Prospect', input()->id);
			}
		} else {
			session()->setFlash("Could not find the prospect. Please try again.", 'warning');
			$this->redirect(SITE_URL . "/?module=Admissions");
		}

		$timeframe = $this->loadModel('Timeframe')->fetchAll();

		// assign smarty objects
		smarty()->assign('timeframe', $timeframe);
		smarty()->assign('prospect', $prospect);
	}


	/* 
	 * Save Prospect
	 *
	 */
	public function save_prospect() {
		$prospect = $this->loadModel('Prospect');
		
		// save the first name
		if (input()->first_name != null) {
			$prospect->first_name = input()->first_name;
		} else {
			session()->setFlash("Please enter a first name", 'danger');
			$this->redirect(input()->current_url);
		}

		// save the last name
		if (input()->last_name != null) {
			$prospect->last_name = input()->last_name;
		} else {
			session()->setFlash("Please enter a last name", 'danger');
			$this->redirect(input()->current_url);
		}

		// save the email address
		if (input()->email_address != null) {
			$prospect->email = input()->email_address;
		} 

		// save the phone number
		if (input()->phone != null) {
			$prospect->phone = input()->phone;
		} else {
			session()->setFlash("Please enter a phone number", 'danger');
			$this->redirect(input()->current_url);			
		}

		// set admission date based on estimated timeline
		if (input()->timeframe != null) {
			$prospect->timeframe = input()->timeframe;
		}

		// make the prospect active
		$prospect->active = 1;

		if ($prospect->save()) {
			session()->setFlash("{$prospect->first_name} {$prospect->last_name} was created as a new prospect", 'success');	
			$this->redirect(SITE_URL . "/?module=Admissions");		
		}

	}


}