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

		if (isset (input()->pipeline)) {
			if (input()->pipeline == 'leads') {
				smarty()->assign('is_prospect', false);
				$this->leads();
			} elseif (input()->pipeline == 'prospects') {
				smarty()->assign('is_prospect', true);
				$this->prospects();
			}
		} else {
			smarty()->assign('is_prospect', true);
			$this->prospects();
		}
		
	}



			
	/* 
	 * Prospects Page
	 *
	 */
	public function prospects() {
		smarty()->assign('title', "Current Prospects");

		// get prospects list
		$prospects = $this->loadModel('Prospect')->fetchProspects();

		smarty()->assign('prospects', $prospects);
		smarty()->assign('page_title',"Current Prospects");

	}


			
	/* 
	 * Leads Page
	 *
	 */
	public function leads() {
		// get prospects list
		$prospects = $this->loadModel('Prospect')->fetchProspects(false);
		smarty()->assign('prospects', $prospects);
		smarty()->assign('page_title',"Current Leads");

	}


	/* 
	 * New Prospect
	 *
	 */
	public function new_lead() {
		// fetch timeframe options
		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		// fetch referral source options
		$referral_sources = $this->loadModel('ReferralSource')->fetchAll();
		// contact relationship
		$contact_type = $this->loadModel('ContactType')->fetchAll();


		smarty()->assign('timeframe', $timeframe);
		smarty()->assign('referral_sources', $referral_sources);
		smarty()->assign('contact_type', $contact_type);

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

		// fetch the resident contacts
		$contacts = $this->loadModel('ContactLink')->fetchContacts($prospect->id);
		// fetch contact types
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		// fetch file types
		$file_type = $this->loadModel('FileType')->fetchAll();
		// fetch states
		$states = $this->loadModel('State')->fetchAll();
		// fetch religion preferences
		$religion_preferences = $this->loadModel('ReligionPreference')->fetchAll();

		// assign smarty objects
		smarty()->assign('prospect', $prospect);
		smarty()->assign('contacts', $contacts);
		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('file_type', $file_type);
		smarty()->assign('states', $states);
		smarty()->assign('religion_preferences', $religion_preferences);
		smarty()->assign('pipeline', input()->pipeline);
	}



			
	/* 
	 * Save Profile
	 *
	 */
	public function save_profile() {
		if (input()->id != null) {
			$prospect = $this->loadModel('Prospect', input()->id);
		} else {
			session()->setFlash("Could not save the profle", 'danger');
			$this->redirect(array('module' => 'Admissions'));
		}

		
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

		if (input()->address != null) {
			$prospect->address = input()->address;
		} 

		if (input()->city != null) {
			$prospect->city = input()->city;
		} 

		if (input()->state != null) {
			$prospect->state = input()->state;
		} 

		if (input()->zip != null) {
			$prospect->zip = input()->zip;
		} 

		if (input()->birthdate != null) {
			$prospect->birthdate = mysql_date(input()->birthdate);
		}

		if (input()->gender != null) {
			$prospect->gender = input()->gender;
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

		if (input()->veteran != null) {
			$prospect->veteran = input()->veteran;
		}

		if (input()->religion_preference != null) {
			$prospect->religion_preference = input()->religion_preference;
		}

		if (input()->profession != null) {
			$prospect->profession = input()->profession;
		}

		if (input()->profession != null) {
			$prospect->profession = input()->profession;
		}

		if (input()->contact_name != null) {
			$prospect->contact_name = input()->contact_name;
		}

		if (input()->background_info != null) {
			$prospect->background_info = input()->background_info;
		}

		if (input()->contact_email != null) {
			$prospect->contact_email = input()->contact_email;
		}

		if (input()->contact_phone != null) {
			$prospect->contact_phone = input()->contact_phone;
		}

		if (input()->pipeline == 'lead') {
			$prospect->active = 0;
		} elseif (input()->pipeline == 'prospect') {
			$prospect->active = 1;
		}


		// save documents


		if ($prospect->save()) {
			session()->setFlash("{$prospect->first_name} {$prospect->last_name} was saved", 'success');	
			$this->redirect(array('module' => "Admissions", 'page' => "admissions", 'action' => "index", 'pipeline' => input()->pipeline . "s"));		
		}


	}



			
	/* 
	 * Convert Lead to Prospect
	 *
	 */
	public function convert_to_prospect() {
		if (input()->id != null) {
			$prospect = $this->loadModel('Prospect', input()->id);
			$prospect->active = 1;

			if ($prospect->save()) {
				session()->setFlash("{$prospect->first_name} {$prospect->last_name} was saved as a current prospect", 'success');
				$this->redirect(array('module' => 'Admissions'));
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
			$prospect = $this->loadModel('Prospect', input()->id);

			if (input()->timeframe != null) {
				$prospect->timeframe = input()->timeframe;
			}

			if ($prospect->save()) {
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

		$prospect = $this->loadModel('Prospect', input()->prospect_id);
		// fetch contact types
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		// fetch states
		$states = $this->loadModel('State')->fetchAll();

		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('states', $states);


		smarty()->assign('prospect', $prospect);
		smarty()->assign('pipeline', input()->pipeline);
	}

			
	/* 
	 * Resident Contact
	 *
	 */
	public function resident_contact() {
		$this->template = 'blank';


		if (isset (input()->prospect_id)) {
			$prospect = $this->loadModel('Prospect', input()->prospect_id);
		} else {
			$prospect = $this->loadModel('Prospect');
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
			$prospect = $this->loadModel('Prospect', input()->prospect_id);
		} else {
			$prospect = $this->loadModel('Prospect');	
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
			$contact_link->prospect = $prospect->id;
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
		$prospect = $this->loadModel('Prospect', input()->prospect);
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
			$prospect = $this->loadModel('Prospect', input()->prospect);	
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