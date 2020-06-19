<?php

class ProfilesController extends AdmissionsController {

	/* 
	 * Resident Profile page
	 *
	 */
	public function index() {
		if (isset (input()->id)) {
			if (input()->id != null) {
				$prospect = $this->loadModel('Client', input()->id);
			}
		} else {
			session()->setFlash("Could not find the prospect. Please try again.", 'warning');
			$this->redirect(SITE_URL . "/?module=Admissions");
		}

		smarty()->assign('title', "Profile for {$prospect->first_name} {$prospect->last_name}");
		
		// fetch the resident contacts
		$contacts = $this->loadModel('ContactLink')->fetchContacts($prospect->id);
		// fetch contact types
		$contact_type = $this->loadModel('ContactType')->fetchAll();
		// fetch file types
		$file_type = $this->loadModel('FileType')->fetchAll();
		// fetch states
		$states = $this->loadModel('State')->fetchAll();
		// fetch religion preferences
		$religion = $this->loadModel('Religion')->fetchAll();
		// fetch prospect files
		$files = $this->loadModel('ClientFile')->fetchFiles($prospect->id);
		
		// assign smarty objects
		smarty()->assign('prospect', $prospect);
		smarty()->assign('contacts', $contacts);
		smarty()->assign('contact_type', $contact_type);
		smarty()->assign('file_type', $file_type);
		smarty()->assign('states', $states);
		smarty()->assign('religion', $religion);
		smarty()->assign('files', $files);
		
		
	}



	/* 
	 * Save Profile
	 *
	 */
	public function save_profile() {
		if (input()->id != null) {
			$prospect = $this->loadModel('Client', input()->id);
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

		if (input()->background_info != null) {
			$prospect->background_info = input()->background_info;
		}


		if ($prospect->save()) {

			// save contacts
			foreach (input()->contact as $contact) {
				$contact_link = $this->loadModel('ContactLink');
				$contact_link->contact = $contact['id'];
				$contact_link->client = $prospect->id;
				$contact_link->contact_type = $contact['contact_type'];

				if (isset ($contact['poa'])) {
					$contact_link->poa = 1;
				}

				if (isset ($contact['primary_contact'])) {
					$contact_link->primary_contact = 1;
				}

				if ($contact_link->save()) {
					$error = false;
				} else {
					$error = true;
					break;
				}
				
			}
			if (!$error) {
				session()->setFlash("{$prospect->first_name} {$prospect->last_name} was saved", 'success');	
				$this->redirect(array('module' => "Admissions", 'page' => "admissions", 'action' => "index"));	
			} else {
				session()->setFlash("{$prospect->first_name} {$prospect->last_name} was not saved", 'danger');	
				$this->redirect(array('module' => "Admissions", 'page' => "admissions", 'action' => "index"));	

			}	
		}

	}



			
	/* 
	 * Upload prospect files
	 *
	 */
	public function uploadFiles() {

		if (isset (input()->prospect)) {
			$prospect = $this->loadModel('Client', input()->prospect);	
		} else {
			return false;
		}

		if (input()->fileType != null) {
			$file_type = input()->fileType;
		} else {
			return false;
		}

		$filename = $_FILES['file']['name'];

		$client_file = $this->loadModel('ClientFile');
		$client_file->client = $prospect->id;
		$client_file->file_type = $file_type;
		$client_file->file_name = $filename;

		// upload file to S3
		if ($this->upload_file($_FILES, 'client_files')) {
			if ($client_file->save()) {

				json_return($client_file->fetchFileTypeName());
			}
		}

		return false;

		
		
	}

}