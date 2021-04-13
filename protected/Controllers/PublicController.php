<?php

class PublicController extends MainPageController {

	public $page = 'public';
	public $template = 'website';
	public $allow_access = true;
	public $title = null;
	public $meta = false;

			
	/* 
	 * Home Page
	 *
	 */
	public function index() {
		smarty()->assign('title', 'Assisted Living in Anchorage Alaska');
	}


			
	/* 
	 * Living at Aspen Creek page
	 *
	 */
	public function living_at_aspen_creek() {

	}



			
	/* 
	 * Virtual Visit
	 *
	 */
	public function virtual_visit() {
		
	}

			
	/* 
	 * Leadership Team page
	 *
	 */
	public function leadership_team() {

	}


			
	/* 
	 * Care Team page
	 *
	 */
	public function care_team() {

	}

    /*
	 * Our Stories page
	 *
	 */
	public function stories() {

	}

			
	/* 
	 * Contact page
	 *
	 */
	public function contact() {
		
	}


	/*
	 * Schedule Visit
	 *
	 */
	public function schedule_visit() {
		
	}


	/*
	 * Memory Care
	 *
	 */
	public function memory_care() {
		
	}


	/*
	 * Spring Market
	 *
	 */
	public function springmarket() {
		$meta = true;

		smarty()->assign('meta', $meta);
	}

	public function vendor() {
		
	}



			
	/* 
	 * Contact form submit
	 *
	 */
	public function submit_contact_form() {
		$data = array();

		// contact name
		if (input()->name != null) {
			$data['post']['name'] = input()->name;
		} 

		// contact email
		if (input()->email != null) {
			$data['post']['email'] = input()->email;
		} 

		$data['post']['subject'] = "Aspen Creek Website Message";

		// contact message
		if (input()->message != null) {
			$data['post']['message_body'] = 
				"Name: " . input()->name . "\n" .
				"Phone: " . input()->phone . "\n" . 
				"Email: " . input()->email . "\n" .
				"Message: \n" . input()->message;
		} 


		// send the email
		if ($this->sendEmail($data)) {
			session()->setFlash("Your message was sent. We will be in contact soon!", 'success');
			$this->redirect(SITE_URL);
		} else {
			session()->setFlash("We could not send your message. Please try again.", 'success');
			$this->redirect(SITE_URL . DS . 'contact');
		}


	}

			
	/* 
	 * Resident Application
	 *
	 */
	public function resident_application() {
		if (isset (input()->id)) {
			$prospect = $this->loadModel('Client', input()->id);
		} else {
			$prospect = $this->loadModel('Client');
		}

		// fetch care needs
		$care_needs = $this->loadModel('Needs')->fetchAll();
		// fetch dementia levels
		$dementia = $this->loadModel('Dementia')->fetchAll();
		// fetch timeframe options
		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		// contact relationship
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		

		smarty()->assign('prospect', $prospect);
		smarty()->assign('care_needs', $care_needs);
		smarty()->assign('dementia', $dementia);
		smarty()->assign('timeframe', $timeframe);
		smarty()->assign('contact_type', $contact_type);
	}



			
	/* 
	 * Save the resident application
	 *
	 */
	public function save_application() {
		// create an empty array to hold flash message errors
		$feedback = array();

		// if there is an id in the url this is an already existing prospect
		// otherwise create an empty prospect object
		if (isset (input()->id)) {
			$prospect = $this->loadModel('Client', input()->id);
		} else {
			$prospect = $this->loadModel('Client');
		}

		$schedule = $this->loadModel('Schedule');
		
		$contact = $this->loadModel('Contact');
		$contact_link = $this->loadModel('ContactLink');

		// set location to Aspen Creek
		// this will need to be update when additional locations are possible
		$location = 26;
		
		
		// load the assessment object
		$assessment = $this->loadModel('Assessment');

		// contact info

		// contact name
		if (input()->contact_first_name != null) {
			$contact->first_name = input()->contact_first_name;
		} else {
			$feedback[] = "Please enter a contact name";
		}

		if (input()->contact_last_name != null) {
			$contact->last_name = input()->contact_last_name;
		} else {
			$feedback[] = "Please enter a contact name";
		}


		// contact type
		if (input()->contact_type != null) {
			$contact_link->contact_type = input()->contact_type;
		} else {
			$feedback[] = "Please select a contact type";
		}

		// contact email 
		if (input()->contact_email != null) {
			$contact->email = input()->contact_email;
		} else {
			$feedback[] = "Please enter a contact email address";
		}

		// phone 
		if (input()->contact_phone != null) {
			$contact->phone = input()->contact_phone;
		} else {
			$feedback[] = "Please enter a contact phone number";
		}

		// first name
		if (input()->first_name != null) {
			$prospect->first_name = input()->first_name;
		} else {
			$feedback[] = "Please enter a first name";
		}

		// last name
		if (input()->last_name != null) {
			$prospect->last_name = input()->last_name;
		} else {
			$feedback[] = "Please enter a last name";
		}

		// email 
		if (input()->email != null) {
			$prospect->email = input()->email;
		}

		// phone 
		if (input()->phone != null) {
			$prospect->phone = input()->phone;
		}

		// birthdate
		if (input()->birthdate != null) {
			$prospect->birthdate = mysql_date(input()->birthdate);
		} else {
			$feedback[] = "Please enter a birthdate";
		}

		// gender
		if (input()->gender != null) {
			$prospect->gender = input()->gender;
		} else {
			$feedback[] = "Please select a gender";
		}

		// diabetes
		if (input()->diabetes != null) {
			$assessment->diabetes = input()->diabetes;
		} else {
			$feedback[] = "Please indicate if the resident has diabetes";
		}
		
		// dementia
		if (input()->dementia != null) {
			$assessment->dementia_id = input()->dementia;
		} else {
			$feedback[] = "Please indicate if the resident has dementia";
		}

		// mental health diagnosis
		if (input()->mh_diagnosis != null) {
			$assessment->mh_diagnosis = input()->mh_diagnosis;
		} else {
			$feedback[] = "Please indicate if the resident has a mental health diagnosis";
		}

		// mental health diagnosis explanation
		if (input()->mh_explanation != null) {
			$assessment->mh_explanation = input()->mh_explanation;
		} elseif (input()->mh_diagnosis == 1) {
			$feedback[] = "Please give an explanation of the mental health diagnosis";
		}

		// chemical dependencies
		if (input()->chemical_dependencies != null) {
			$assessment->chemical_dependencies = input()->chemical_dependencies;
		} else {
			$feedback[] = "Please indicate if the resident has any chemical dependencies";
		}

		// chemical dependencies explanation
		if (input()->dependency_explanation != null) {
			$assessment->dependency_explanation = input()->dependency_explanation;
		} elseif (input()->chemical_dependencies == 1) {
			$feedback[] = "Please give an explanation of the chemical dependencies";
		}

		// ambulation
		if (input()->ambulatory != null) {
			$assessment->ambulatory = input()->ambulatory;
		} else {
			$feedback[] = "Please indicate if the resident is ambulatory";
		}

		// set admission date based on estimated timeline
		if (input()->timeframe != null) {
			$schedule->timeframe = input()->timeframe;
		} else {
			$feedback[] = "Please select an estimated timeframe in which assisted living services may be needed";
		}

		// primary care physician
		// if (input()->pcp_name != null) {
		// 	$prospect->pcp_name = input()->pcp_name;
		// }

		// if (input()->pcp_phone != null) {
		// 	$prospect->pcp_phone = input()->pcp_phone;
		// }

		if (!empty ($feedback)) {
			session()->setFlash($feedback, 'danger');
			if ($prospect->public_id != null) {
				session()->redirect(SITE_URL . '/resident-application/?id=' . $prospect->public_id);
			}
			
			$this->redirect(SITE_URL . '/resident-application');
		}

		$alert = null;

		// save the prospect info
		if ($prospect->save()) {
			$contact_link->client = $prospect->id;

			if ($contact->save()) {
				$contact_link->contact = $contact->id;

				if ($contact_link->save()) {
					$alert = "Your application has been submitted. We will be in touch shortly!";

					// save the admission after the prospect so we can use the newly created prospect id
					$assessment->prospect_id = $prospect->id;
					// save the admission
					if ($assessment->save()) {
						// care needs 
						// this needs to happed after the prospect is saved so that we have an id to use
						// for new residents.
						if (!empty (input()->care_needs)) {
							foreach (input()->care_needs as $need) {
								$care_needs = $this->loadModel('NeedsPatientLink');
								$care_needs->care_needs_id = $need;
								$care_needs->prospect_id = $prospect->id;
								$care_needs->save();
							}
						} 
						$schedule->client = $prospect->id;
						$schedule->location = $location;
						// set status as a prospect
						$schedule->status = 2;

						$schedule->save();

						// send an email notification
						$data = array();
						$data['post']['subject'] = "New Resident Aplication";

						// contact message
						$data['post']['message_body'] = 
							"Name: " . $prospect->first_name . " " . $prospect->last_name . "\n" .
							"Phone: " . $prospect->phone . "\n" . 
							"Email: " . $prospect->email . "\n" .
							"Message: \n" . "There is a new resident application waiting for you!";

						$this->sendEmail($data);

					} else {
						$alert = "Could not submit the application. Please try again.";
					}
				
				}
			} else {
				$alert = "Could not submit the form. Please try again";
			}

		} else {
			$alert = "Could not send the application. Please try again.";
		}

		session()->setFlash($alert, 'success');
		$this->redirect(SITE_URL);

		
	}


			
	/* 
	 * FAQ page
	 *
	 */
	public function faq() {

	}


			
	/* 
	 * Error page
	 *
	 */
	public function error() {
		
	}


			
	/* 
	 * Careers page
	 *
	 */
	public function careers() {
		
	}


			
	/* 
	 * Activities page
	 *
	 */
	public function activities() {
		// number of days to show for activities
		$numDays = 5;
		// the number of days count should be 1 less than the number of days to display
		$numDaysCount = $numDays - 1;

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d', strtotime("{$start_date} + {$numDaysCount}"));

		$location = $this->loadModel('Location', 26);
		$activities = $this->loadModel('Activity')->fetchActivities($location->id, $start_date, $numDaysCount);

		smarty()->assign('startDate', $start_date);
		smarty()->assign('activities', $activities);
	}


			
	/* 
	 * Menu page
	 *
	 */
	public function menu() {

		smarty()->assign('title', "Current Menu");

		// set the number of days to display
		$numDays = 5;
		$numDaysCount = $numDays - 1;

		$week = Calendar::getNextDays($numDays);

		$_dateStart = date('Y-m-d', strtotime($week[0]));
		$_dateEnd = date('Y-m-d', strtotime($week[$numDaysCount]));

		if (strtotime($_dateStart) > strtotime('now')) {
			$today = date('Y-m-d', strtotime('now'));
		} else {
			$today = false;
		}

		smarty()->assign('startDate', $_dateStart);

		// Aspen Creek is location id 26
		$location = $this->loadModel("Location", 26);

		// Get the menu id the facility is currently using
		$menu = $this->loadModel('Menu')->fetchMenu($location->id, $_dateStart);
		smarty()->assign('menu', $menu);

		// Get the menu day for today
		$numDays = $this->loadModel('MenuItem')->fetchMenuDay($menu->menu_id);
		$startDay = round($this->dateDiff($menu->date_start, $_dateStart) % $numDays->count + 1);
		$endDay = $startDay + $numDaysCount;


		// Get the menu items for the week
		$menuItems = $this->loadModel('MenuItem')->fetchMenuItems($location->id, $_dateStart, $_dateEnd, $startDay, $endDay, $menu->menu_id);
		$this->normalizeMenuItems($menuItems);

		// get alternate menu items
		$alternates = $this->loadModel('Alternate')->fetchAlternates($location->id);
		smarty()->assign('alternates', $alternates);

		smarty()->assign('count', 0);

	}


	/* 
	 * Tour form
	 *
	 */
	public function tour_form() {
		$this->allow_access = true;
		$this->template = 'blank';

		$timeframe = $this->loadModel('Timeframe')->fetchAll();
		smarty()->assign('timeframe', $timeframe);

		$contact_type = $this->loadModel('ContactType')->fetchAll();
		smarty()->assign('contact_type', $contact_type);

		$referral_source = $this->loadModel('ReferralSource')->fetchAll();
		smarty()->assign('referral_source', $referral_source);

		$payor_source = $this->loadModel('PayorSource')->fetchAll();
		smarty()->assign('payor_source', $payor_source);
			
		
	}


			
	/* 
	 * Submit Tour Form
	 *
	 */
	public function submit_tour_form() {
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
						$this->redirect(SITE_URL . '/tour-form');
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
		$this->redirect(SITE_URL . '/tour-form');


	}


}