<?php

class CaseManagersController extends MainPageController {


	public function manage() {
		if (isset (input()->location)) {
			$loc_id = input()->location;
		} else {
			//	Fetch the users default location
			$user = auth()->getRecord();
			$loc_id = $user->default_location;
		}
		$location = $this->loadModel('Location', $loc_id);
		smarty()->assign('location_id', $location->public_id);

		if (isset (input()->orderBy)) {
			$_orderBy = input()->orderBy;
		} else {
			$_orderBy = false;
		}

		$class = $this->loadModel("CaseManager")->fetchManageData($location, $_orderBy);

		$classArray[0] = array();
		if (!empty ($class)) {
			foreach ($class as $key => $value) {
				foreach ($value as $k => $v) {
					$classArray[$key][$k] = $v;
					if (!in_array($k, $value->fetchFields())) {
						unset($classArray[$key][$k]);
					}

				}

			}
		}

		smarty()->assign('data', $classArray);


	}


	public function case_manager() {
		smarty()->assignByRef('location', $this->loadModel('Location', input()->location));

		if (isset (input()->isMicro)) {
			smarty()->assign('isMicro', true);
		} else {
			smarty()->assign('isMicro', false);
		}


		if (input()->type == "add") {
			smarty()->assign('title', "Add Case Manager");
			smarty()->assign('pageHeader', "Add a Case Manager");

			// load an empty case manager object
			$cm = $this->loadModel('CaseManager');
		} elseif (input()->type == "edit") {
			smarty()->assign('title', "Edit Case Manager");
			smarty()->assign('pageHeader', "Edit Case Manager");

			// need to get the existing case manager info
			$cm = $this->loadModel('CaseManager', input()->id);


		} else {
			session()->setFlash("Could not load page. Please try again.");
			$this->redirect(input()->current_url);
		}


		smarty()->assignByRef('cm', $cm);
		smarty()->assignByRef('healthcareFacility', $this->loadModel('HealthcareFacility', $cm->healthcare_facility_id));

	}


	public function submit_add() {
		//	Right now everyone has the ability to add healthcare facilities
		if (!auth()->hasPermission("manage_case_managers")) {
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
				$this->redirect(array('page' => 'case_managers', 'action' => 'manage'));
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

	public function searchReferralSource() {
		$this->template = "blank";

		$term = input()->query;
		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
			$additionalStates = $this->loadModel('LocationLinkState')->getAdditionalStates($location->id);
		}

		$result = $this->loadModel('CaseManager')->searchByName($term);

		$resultArray = array();
		if (!empty ($result)) {
			foreach ($result as $k => $r) {
				$resultArray['suggestions'][$k]['value'] = $r->last_name . ', ' . $r->first_name ;
				$resultArray['suggestions'][$k]['data'] = $r->id;
			}
		}

		json_return($resultArray);

	}


}
