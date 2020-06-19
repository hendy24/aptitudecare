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

		// fetch timeframe options
		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		smarty()->assign('timeframe', $timeframe);

		if (isset (input()->filterBy)) {
			$filter_by = input()->filterBy;
		} else {
			$filter_by = 'lead';
		}
		
		smarty()->assign('activeTab', $filter_by);
		
		
		$status = $this->loadModel('Status')->fetchAll();
		smarty()->assign('status', $status);

		if (isset (input()->sortBy)) {
			$sort_by = input()->sortBy;
		} else {
			$sort_by = 'timeframe';
		}
		
		// get the location
		// if (isset (input()->location)) {
		// 	$location = $this->loadModel('Location', input()->location);
		// } else {
		// 	$location = $this->getLocation();
		// }

		// get prospects list
		$prospects = $this->loadModel('Client')->fetchProspects($filter_by, $sort_by);
		smarty()->assign('prospects', $prospects);
		
	}



			
	/* 
	 * New Prospect
	 *
	 */
	public function new_prospect() {
		// fetch timeframe options
		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		// fetch referral source options
		$referral_sources = $this->loadModel('ReferralSource')->fetchAll();
		// contact relationship
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		$states = $this->loadModel('State')->fetchAll();


		smarty()->assign('timeframe', $timeframe);
		smarty()->assign('referral_sources', $referral_sources);
		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('states', $states);
		
		
	}



			
	/* 
	 * Save Prospect
	 *
	 */
	public function save_prospect() {

		pr (input()); exit;
		$prospect = $this->loadModel('Client');
		$contact = $this->loadModel('Contact');
		$contact_link = $this->loadModel('ContactLink');
		$schedule = $this->loadModel('Schedule');
		$location = $this->getLocation();
		
		

		if (input()->resident_first_name != null) {
			$prospect->first_name = input()->resident_first_name;
		}

		if (input()->resident_last_name != null) {
			$prospect->last_name = input()->resident_last_name;
		}
	
		if (input()->resident_email != null) {
			$prospect->email = input()->resident_email;
		}

		if (input()->resident_phone != null) {
			$prospect->phone = input()->resident_phone;
		}

		if (input()->timeframe != null) {
			$schedule->timeframe = input()->timeframe;
		}

		if (input()->payor_source != null) {
			$prospect->payor_source = input()->payor_source;
		}

		// primary contact
		if (input()->contact_first_name != null) {
			$contact->first_name = input()->contact_first_name;
		}

		if (input()->contact_last_name != null) {
			$contact->last_name = input()->contact_last_name;
		}

		if (input()->contact_email != null) {
			$contact->email = input()->contact_email;
		}

		if (input()->contact_phone != null) {
			$contact->phone = input()->contact_phone;
		}

		if (input()->contact_type != null) {
			$contact_link->contact_type = input()->contact_type;
		}

		if (input()->referral_source != null) {
			$prospect->referral_source = input()->referral_source;
		}

		// set the admit status to "Lead"
		$schedule->status = 1;

		// set location
		$schedule->location = $location->id;

		// set first contact date
		$schedule->datetime_first_contact = mysql_date();
		

		$feedback = array();

		// save contact and prospect
		if ($contact->save()) {
			if ($prospect->save()) {
				// set data for contact link now that it is saved
				$contact_link->contact = $contact->id;
				$contact_link->client = $prospect->id;
				

				if ($contact_link->save()) {
					$schedule->client = $prospect->id;

					if ($schedule->save()) {
						session()->setFlash("Thank you! Your information was sent!", 'success');
						$this->redirect(SITE_URL . '/?module=Admissions&page=admissions');
					} else {
						$feedback[] = "We were not able to save the information. Please try again.";
					}
				}

			} else {
				$feedback[] = "We were not able to save the potential resident information. Please try again.";
			}
		} else {
			$feedback[] = "We were not able to save the contact information. Please try again.";
		}
		
		session()->setFlash($feedback, 'danger');
		$this->redirect(SITE_URL . '/?module=Admissions&page=admissions');

	}




			
	/* 
	 * Save new prospect
	 *
	 */
	public function save_new_prospect() {

		$prospect = $this->loadModel('Client');
		$schedule = $this->loadModel('Schedule');
		$location = $this->getLocation();

		if (input()->first_name != null) {
			$prospect->first_name = input()->first_name;
		}

		if (input()->last_name != null) {
			$prospect->last_name = input()->last_name;
		}
	
		if (input()->email != null) {
			$prospect->email = input()->email;
		}

		if (input()->phone != null) {
			$prospect->phone = input()->phone;
		}

		if (input()->timeframe != null) {
			$schedule->timeframe = input()->timeframe;
		}

		if (input()->referral_source != null) {
			$prospect->referral_source = input()->referral_source;
		}
		
		$feedback = array();

		// save contact and prospect
		if ($prospect->save()) {
			foreach(input()->contact as $c) {
				$contact_link = $this->loadModel('ContactLink');
				$contact_link->contact = $c['id'];
				$contact_link->client = $prospect->id;
				$contact_link->contact_type = $c['contact_type'];

				if (isset ($c['poa'])) {
					$contact_link->poa = 1;
				}

				if (isset ($c['primary_contact'])) {
					$contact_link->primary_contact = 1;
				}

				$contact_link->save();
			}	

			$schedule->client = $prospect->id;
			// set location
			$schedule->location = $location->id;
			// set first contact date
			$schedule->datetime_first_contact = mysql_date();
			$schedule->timeframe = input()->timeframe;	
			// set the status as a new lead
			$schedule->status = 1;	

			if ($schedule->save()) {
				session()->setFlash("{$prospect->first_name} {$prospect->last_name} was added as a new prospect.", 'success');
				$this->redirect(SITE_URL . '/?module=Admissions&page=admissions');
			} else {
				$feedback[] = "We were not able to save the new prospect. Please try again.";
			}
		} else {
			$feedback[] = "We were not able to save the new prospect. Please try again.";
		}
		
		session()->setFlash($feedback, 'danger');
		$this->redirect(SITE_URL . '/?module=Admissions&page=admissions');

	}


			
	/* 
	 * Convert Lead to Prospect
	 *
	 */
	public function convert_to_prospect() {
		if (input()->id != null) {
			$prospect = $this->loadModel('Client', input()->id);
			$schedule = $this->loadModel('Schedule')->fetchSchedule($prospect->id);
			// set the status as a prospect
			$schedule->status = 2;
			

			if ($schedule->save()) {
				session()->setFlash("{$prospect->first_name} {$prospect->last_name} was saved as a current prospect", 'success');
				$this->redirect(array('module' => 'Admissions', 'page' => 'admissions', 'action' => 'index', 'filterBy' => 'prospect'));
			} else {
				session()->setFlash("Could not make this lead a current prospect. Please try again.", 'danger');
			}
		}
		
	}



			
	/* 
	 * Change timeframe
	 *
	 */
	public function change_timeframe() {
		if (input()->id != null) {
			$prospect = $this->loadModel('Client', input()->id);
			
			$schedule = $this->loadModel('Schedule')->fetchSchedule($prospect->id);

			if (input()->timeframe != null) {
				$schedule->timeframe = input()->timeframe;
			}

			if ($schedule->save()) {
				return true;
			} 

			return false;
		}


		return false;
		exit;

	}



			
	/* 
	 * Add a contact
	 *
	 */
	public function add_contact() {
		$this->template = 'blank';

		$prospect = $this->loadModel('Client', input()->prospect_id);
		// fetch contact types
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		// fetch states
		$states = $this->loadModel('State')->fetchAll();

		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('states', $states);


		smarty()->assign('prospect', $prospect);
	}

			
	/* 
	 * Resident Contact
	 *
	 */
	public function resident_contact() {
		$this->template = 'blank';


		if (isset (input()->prospect_id)) {
			$prospect = $this->loadModel('Client', input()->prospect_id);
		} else {
			$prospect = $this->loadModel('Client');
		}

		if (isset (input()->contact_id)) {
			$contact = $this->loadModel('Contact', input()->contact_id);
		} else {
			$contact = $this->loadModel('Contact');
		}

		if (isset (input()->contact_link)) {
			$contact_link = $this->loadModel('ContactLink', input()->contact_link);
		} else {
			$contact_link = $this->loadModel('ContactLink');
			
		}

		// see if the contact is linked to the prospect
		//$contact_link = $this->loadModel('ContactLink')->findExistingLink($prospect, $contact);


		// fetch contact types
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		// fetch states
		$states = $this->loadModel('State')->fetchAll();


		smarty()->assign('contact', $contact);
		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('contact_link', $contact_link);
		smarty()->assign('states', $states);
		smarty()->assign('resident_id', input()->prospect_id);

	}



			
	/* 
	 * Save Resident Contact
	 *
	 */
	public function save_contact() {

		// if prospect_id is set then fetch the info from the db	
		if (isset (input()->prospect_id)) {
			$prospect = $this->loadModel('Client', input()->prospect_id);
		} else {
			$prospect = $this->loadModel('Client');	
		}
		
		// if contact_id is set then fetch the info from the db	
		if (isset (input()->contact_id)) {
			$contact = $this->loadModel('Contact', input()->contact_id);
		} else {
			$contact = $this->loadModel('Contact');
		}
	
		// if contact_link is set then fetch the info from the db	
		if (isset (input()->contact_link)) {
			$contact_link = $this->loadModel('ContactLink', input()->contact_link);
		} else {
			$contact_link = $this->loadModel('ContactLink');	
		}
		

		// set data submitted from the form
		if (input()->first_name != null) {
			$contact->first_name = input()->first_name;
		}

		if (input()->last_name != null) {
			$contact->last_name = input()->last_name;
		}

		if (input()->email != null) {
			$contact->email = input()->email;
		}

		if (input()->phone != null) {
			$contact->phone = input()->phone;
		}

		if (input()->address != null) {
			$contact->address = input()->address;
		}

		if (input()->city != null) {
			$contact->city = input()->city;
		}

		if (input()->state != null) {
			$contact->state = input()->state;
		}

		if (input()->zip != null) {
			$contact->zip = input()->zip;
		}

		if (isset (input()->poa)) {
			if (input()->poa == 1) {
				// **TO-DO: need to check for existing first
				if ($prospect->id != null) {
					$this->loadModel('ContactLink')->checkExistingLegalAuthority($prospect->id, 'poa');
				}
				$contact_link->poa = 1;
			} else {
				$contact_link->poa = 0;
			}
		} else {
			$contact_link->poa = 0;
		}

		if (isset (input()->primary_contact)) {
			if (input()->primary_contact == 1) {
				// **TO-DO: need to check for existing first
				if ($prospect->id != null) {
					$this->loadModel('ContactLink')->checkExistingLegalAuthority($prospect->id, 'primary_contact');
				}
				$contact_link->primary_contact = 1;
			} else {
				$contact_link->primary_contact = 0;
			}
		} else {
			$contact_link->primary_contact = 0;
		}

		// save the new contact
		if ($contact->save()) {

			// delete the contact to the prospect
			//$contact_link = $this->loadModel('ContactLink')->deleteCurrentLinks($contact->id, $prospect->id);

			$contact_link->contact = $contact->id;
			$contact_link->client = $prospect->id;
			$contact_link->contact_type = input()->contact_type;

			if ($contact_link->save()) {
				return true;
			} 	
		}

		return false;

	}



			
	/* 
	 * Fetch Contact Names
	 *
	 */
	public function fetchContactNames() {
		json_return (array('suggestions' => $this->loadModel('Contact')->fetchNames(input()->query)));
	}



			
	/* 
	 * Save new contact to prospect
	 *
	 */
	public function linkContact() {
		$prospect = $this->loadModel('Client', input()->prospect);
		$contact = $this->loadModel('Contact', input()->contact);
		$contact_link = $this->loadModel('ContactLink')->findExisting($prospect, $contact, input()->contact_type);

		if ($contact_link->id == null) {
			$contact_link->prospect = $prospect->id;
			$contact_link->contact = $contact->id;
			$contact_link->contact_type = input()->contact_type;

			if ($contact_link->save()) {
				return true;
			}		
		}

		return false;
	}




			
	/* 
	 * Add new contact
	 *
	 */
	public function addNewContact() {

		$contact = $this->loadModel('Contact');

		if (input()->first_name != null) {
			$contact->first_name = input()->first_name;
		}

		if (input()->last_name != null) {
			$contact->last_name = input()->last_name;
		}

		if (input()->email != null) {
			$contact->email = input()->email;
		}

		if (input()->phone != null) {
			$contact->phone = input()->phone;
		}

		if (input()->address != null) {
			$contact->address = input()->address;
		}

		if (input()->city != null) {
			$contact->city = input()->city;
		}

		if (input()->state != null) {
			$contact->state = input()->state;
		}

		if ($contact->save()) {
			json_return($contact);
		}

		return false;
		
	}

			
	/* 
	 * Assign a Room
	 *
	 */
	public function assign_room() {

		$location = $this->getLocation();

		// fetch already scheduled residents
		$scheduled = $this->loadModel('Patient')->fetchPatients($location->id);

		// get rooms
		$rooms = $this->loadModel('Room')->fetchEmpty($location->id);

		$current_residents = $this->loadModel('Room')->mergeRooms($rooms, $scheduled);


		$rooms = array();
		foreach ($current_residents as $resident) {
			if (get_class($resident) == 'Room') {				
				$rooms[] = $resident;
			}
		}
		
		if (input()->prospect != null) {
			$prospect = $this->loadModel('Client', input()->prospect);	
		} else {
			session()->setFlash("Could not find the prospect.", 'danger');
			$this->redirect(array('module' => "Admissions", 'page' => "admissions"));
		}
		
		smarty()->assign('prospect', $prospect);
		smarty()->assign('rooms', $rooms);

	}

	public function save_room_assignment() {
		$location = $this->getLocation();

		$schedule = $this->loadModel('Schedule');
		$schedule->client = $this->loadModel('Client', input()->prospect)->id;
		$schedule->room = input()->room;
		$schedule->location = $location->id;
		$schedule->status = 'Pending';
		
		
		if ($schedule->save()) {
			session()->setFlash("The room was assigned", 'success');
		} else {
			session()->setFlash("Could not assign the room", 'danger');
		}

		$this->redirect(array('module' => "Admissions", 'page' => "admissions", 'action' => "index"));
	}


			
	/* 
	 * Current Admissions
	 *
	 */
	public function scheduled_admissions() {
		
	}



			
	/* 
	 * Unlink Contact
	 *
	 */
	public function unlink_contact() {

		if (input()->prospect) {
			$prospect = $this->loadModel('Client', input()->prospect);	
		}
		if (input()->contact) {
			$contact = $this->loadModel('Contact', input()->contact);
			
		}
		if (input()->contact_link) {
			$contact_link = $this->loadModel('ContactLink', input()->contact_link);	
		}

		if ($contact_link->unlinkContact($prospect->id, $contact->id, $contact_link->id)) {
			return true;
		}

		return false;
	}			

}