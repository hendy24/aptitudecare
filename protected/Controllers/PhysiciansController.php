<?php

class PhysiciansController extends MainPageController {

	public function searchPhysicians() {
		$this->template = 'blank';
		
		$term = input()->query;
		if (isset (input()->location)) {
			$location = $this->loadModel('Location', input()->location);
			$additionalStates = $this->loadModel('LocationLinkState')->getAdditionalStates($location->id);
		}

		$result = $this->loadModel('Physician')->searchByName($term);

		$resultArray = array();
		if (!empty ($result)) {
			foreach ($result as $k => $r) {
				$resultArray['suggestions'][$k]['value'] = $r->last_name . ', ' . $r->first_name ;
				$resultArray['suggestions'][$k]['data'] = $r->id;
			}
		}

		json_return($resultArray);
	}



	public function manage() {
		smarty()->assign('title', "Manage Physicians");
		if (isset(input()->location)) {
			$location = $this->loadModel('Location', input()->location);
		} else {
			$location = $this->loadModel('Location', auth()->getRecord()->default_location);
		}
		smarty()->assign('location_id', $location->public_id);

		if (isset (input()->orderBy)) {
			$_orderBy = input()->orderBy;
		} else {
			$_orderBy = false;
		}

		$physicians = $this->loadModel('Physician')->fetchManageData($location);
		smarty()->assignByRef('physicians', $physicians);
	}


	public function physician() {
		//	We are only going to allow facility administrators and better to add data
		if (!auth()->hasPermission('manage_physicians')) {
			$this->redirect();
		}

		if (isset (input()->isMicro)) {
			smarty()->assign('isMicro', true);
		} else {
			smarty()->assign('isMicro', false);
		}


		if (input()->type == "add") {
			smarty()->assign('title', "Add Physician");
			smarty()->assign('pageHeader', "Add a Physician");

			// load empty physician object
			$p = $this->loadModel('Physician');
		} elseif (input()->type == "edit") {
			smarty()->assign('title', "Edit Physician");
			smarty()->assign('pageHeader', "Edit Physician");

			if (input()->id != '') {
				$p = $this->loadModel('Physician', input()->id);
			} else {
				$this->redirect();
			}

		} else {
			session()->setFlash("Could not load the page. Please try again.");
			$this->redirect(input()->current_url);
		}


		
		smarty()->assignByRef('physician', $p);
	}


	public function submitAdd() {
		if (!auth()->hasPermission('manage_physicians')) {
			$error_messages[] = "You do not have permission to add new physicians";
			session()->setFlash($error_messages, 'danger');
			$this->redirect();
		}

		if (isset (input()->id)) {
			$id = input()->id;
		} else {
			$id = null;
		}

		$physician = $this->loadModel('Physician', $id);

		if (input()->first_name != '') {
			$physician->first_name = input()->first_name;
		} else {
			$error_messages[] = "Enter the physician's first name";
		}

		if (input()->last_name != '') {
			$physician->last_name = input()->last_name;
		} else {
			$error_messages[] = "Enter the physician's last name";
		}

		if (input()->address != '') {
			$physician->address = input()->address;
		} 

		if (input()->city != '') {
			$physician->city = input()->city;
		} else {
			$error_messages[] = "Enter the city";
		} 

		if (input()->state != '') {
			$physician->state = input()->state;
		} else {
			$error_messages[] = "Enter the state";
		} 

		if (input()->zip != '') {
			$physician->zip = input()->zip;
		} else {
			$error_messages[] = "Enter the zip code";
		} 

		if (input()->phone != '') {
			$physician->phone = input()->phone;
		}

		if (input()->fax != '') {
			$physician->fax = input()->fax;
		}

		if (input()->email != '') {
			$physician->email = input()->email;
		}
		
		//	Breakpoint
		if (!empty($error_messages)) {
			session()->setFlash($error_messages, 'warning');
			$this->redirect(input()->path);
		}


		if ($physician->save()) {
			session()->setFlash("Successfully added/edited {$physician->first_name} {$physician->last_name}", 'success');
			if (!isset (input()->isMicro) || input()->isMicro == 0) {
				$this->redirect(array('page' => 'physicians', 'action' => 'manage'));
			} else {
				$this->redirect(array('page' => 'data', 'action' => 'close'));
			}
			
		}

	}

	public function getAdditionalData($data = false) {

	}

}