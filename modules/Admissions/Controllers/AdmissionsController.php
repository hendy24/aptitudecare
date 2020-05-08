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

	}



	/* 
	 * Save Prospect
	 *
	 */
	public function save_prospect() {
		$resident = $this->loadModel('Patient');
		$prospect = $this->loadModel('Prospect');
		
		// save the first name
		if (input()->first_name != null) {
			$resident->first_name = input()->first_name;
		} else {
			session()->setFlash("Please enter a first name", 'danger');
			$this->redirect(input()->current_url);
		}

		// save the last name
		if (input()->last_name != null) {
			$resident->last_name = input()->last_name;
		} else {
			session()->setFlash("Please enter a last name", 'danger');
			$this->redirect(input()->current_url);
		}

		// save the email address
		if (input()->email_address != null) {
			$resident->email_address = input()->email_address;
		} 

		// save the phone number
		if (input()->phone != null) {
			$resident->phone = input()->phone;
		} else {
			session()->setFlash("Please enter a phone number", 'danger');
			$this->redirect(input()->current_url);			
		}

		// set admission date based on estimated timeline
		if (input()->timeframe != null) {
			$prospect->timeframe = input()->timeframe;

			if (input()->timeframe == "1-2_weeks") {
				$prospect->admit_date = date("Y-m-d", strtotime("now + 14 days"));
			}

			if (input()->timeframe == "2-4_weeks") {
				$prospect->admit_date = date("Y-m-d", strtotime("now + 28 days"));
			}

			if (input()->timeframe == "1-2_months") {
				$prospect->admit_date = date("Y-m-d", strtotime("now + 60 days"));
			}

			if (input()->timeframe == "2-6_months") {
				$prospect->admit_date = date("Y-m-d", strtotime("now + 180 days"));
			}

			if (input()->timeframe == "6+_months") {
				$prospect->admit_date = date("Y-m-d", strtotime("now + 200 days"));
			}
		}

		// make the prospect active
		$prospect->active = 1;

		if ($resident->save()) {
			$prospect->patient = $resident->id;
			if ($prospect->save()) {
				session()->setFlash("{$resident->first_name} {$resident->last_name} was created as a new prospect", 'success');	
				$this->redirect(SITE_URL . "/?module=Admissions");		
			}
		}

	}


}