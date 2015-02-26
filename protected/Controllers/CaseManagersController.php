<?php

class CaseManagersController extends MainPageController {

	public function submitAdd() {
		//	Right now everyone has the ability to add healthcare facilities
		if (!auth()->has_permission(input()->action, 'case_managers')) {
			$this->redirect();
		}

		if (isset (input()->id)) {
			$id = input()->id;
		} else {
			$id = null;
		}

		//	Instantiate the model
		$case_manager = $this->loadModel('CaseManager', $id);

		//	Validate form data
		if (input()->first_name != '') {
			$case_manager->first_name = input()->first_name;
		} else {
			$error_messages[] = "Enter the first name";
		}

		if (input()->last_name != '') {
			$case_manager->last_name = input()->last_name;
		} else {
			$error_messages[] = "Enter the last name";
		}

		if (input()->phone != '') {
			$case_manager->phone = input()->phone;
		} else {
			$error_messages[] = "Enter the phone number";
		}

		if (input()->fax != '') {
			$case_manager->fax = input()->fax;
		} 

		if (input()->email != '') {
			$case_manager->email = input()->email;
		} 

		if (input()->healthcare_facility_id != '') {
			$case_manager->healthcare_facility_id = input()->healthcare_facility_id;
		} else {
			$error_messages[] = "Enter the healthcare facility";
		}

		//	Breakpoint
		if (!empty ($error_messages)) {
			session()->setFlash($error_messages, 'error');
			$this->redirect(input()->path);
		}

		if (isset ($case_manager->healthcare_facility)) {
			unset ($case_manager->healthcare_facility);
		}


		if ($case_manager->save()) {
			session()->setFlash("Successfully added/edited {$case_manager->first_name} {$case_manager->last_name}", 'success');
			if (isset (input()->isMicro) && input()->isMicro == true) {
				$this->redirect(array('page' => 'data', 'action' => 'close'));
			} else {
				$this->redirect(array('page' => 'data', 'action' => 'manage', 'type' => 'case_managers'));				
			}
		}

	}

	public function getAdditionalData($data = false) {
		if ($data) {
			smarty()->assign('healthcare_facility_id', $data->healthcare_facility_id);
			$healthcare_facility = $this->loadModel('HealthcareFacility', $data->healthcare_facility_id);
			smarty()->assign('healthcare_facility', $healthcare_facility->name);
		}
		
	}


}